<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use App\Item;
use App\Column;
use App\Value;
use Illuminate\Support\Facades\Log;
use Exception;
use Artisan;
use Illuminate\Database\QueryException;
use Auth;
use File;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\SqlUploadRequest;

class AdminController extends Controller
{

	public function __construct () {
		// User must be an admin to use this controller
		$this->middleware('admin');
	}

	public function index () {

		$columns = getColumns();

		return view('admin/admin', compact('columns'));

	}

	public function backupSQL () {
		Artisan::call('db:backup');
		$message = Auth::user()->name." ran a database backup.";
		Log::debug($message);
		session()->flash('message', $message);
		return redirect('/admin');
	}

	public function recoverSQL (SqlUploadRequest $request) {
		$parameters = $request->all();
		$backup_name = $request->get('backup-name');

		if ($backup_name) {
			$sql_path = storage_path('backups/'.$backup_name);
		} else {
			$file = $request->file('file');
			$mimeType = $file->getClientMimeType();
			$guessedExtension = $file->guessClientExtension();
			$valid_sql = $mimeType == 'application/sql' && $guessedExtension == 'sql';
			if (!$valid_sql) {
				$error = 'Not valid SQL.';
				Log::debug("Invalid SQL import attempted by ".Auth::user()->name.". Mime Type: $mimeType, Extension: .$guessedExtension");
				return back()->withErrors($error)->withInput(request()->all());
			}
			$sql_path = $file->getRealPath();
		}

		$file_exists = File::exists($sql_path);
		if (!$file_exists) {
			$error = 'SQL file does not exist.';
			return back()->withErrors($error)->withInput(request()->all());
		}

		Artisan::call('db:recover "'.$sql_path.'"');
		$message = Auth::user()->name." ran a database recovery.";
		Log::debug($message);
		session()->flash('message', $message);
		return redirect('/admin');
	}

	public function recoverSQLSelect () {
		$path = storage_path('backups/');
		$sql_files = array_reverse(Storage::disk('backups')->files());
		return view('admin.database.select', compact('sql_files'));
	}

	public function recoverSQLUpload () {
		return view('admin.database.upload');
	}

	public function restore () {

		$deleted_items = Item::onlyTrashed()->paginate(100);

		return view('admin/deleted/restore', compact('deleted_items'));
	}

	public function restoreUpdate () {
		$items = session()->get('items', null);
		if ($items !== null) {
			restoreItems($items);
		}

		$message = Auth::user()->name." ran an item restore.";
		Log::debug($message);
		session()->flash('message', $message);
		session()->forget('items');

		return redirect('/admin/deleted/restore');
	}

	public function confirmRestore () {
		$request = request();
		$parameters = $request->all();

		$action = $parameters['action'] ?? null;

		$item_ids = $parameters['item-ids'] ?? null;

		if ($action == 'restore-all' || $action == 'delete-all') {
			$items = Item::onlyTrashed()->get();
		} else if ($item_ids !== null) {
			$items = getDeletedItemsByID($item_ids);
		} else {
			$items = null;
		}

		if ($items !== null && $items->count() > 0) {
			$total = $items->count();
			session()->put('items', $items);
		} else {
			$error = 'No items selected';
			return back()->withErrors($error)->withInput(request()->all());
		}

		if ($action == 'delete' || $action == 'delete-all') {
			return view('/admin/deleted/confirmDelete', compact('total'));
		} else if ($action == 'restore' || $action == 'restore-all') {
			return view('/admin/deleted/confirmRestore', compact('total'));
		} else {
			$error = 'No action chosen';
			return back()->withErrors($error)->withInput(request()->all());
		}
	}

	public function delete () {
		$items = session()->get('items', null);
		if ($items !== null) {
			permanentlyDeleteItems($items);
		}

		$message = Auth::user()->name." permanently deleted ".$items->count()." items.";
		Log::debug($message);
		session()->flash('message', $message);
		session()->forget('items');

		return redirect('/admin/deleted/restore');
	}

}
