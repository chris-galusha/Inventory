<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Column;
use App\Type;
use App\Column_Value;
use App\Value;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Role;
use App\User;
use Hash;

class DatabaseInstaller extends Command
{
  /**
  * The name and signature of the console command.
  *
  * @var string
  */
  protected $signature = 'db:install';

  /**
  * The console command description.
  *
  * @var string
  */
  protected $description = 'Build the initial database entries and relationships required.';

  /**
  * Create a new command instance.
  *
  * @return void
  */

  public function __construct()
  {
    parent::__construct();
  }

  public function install () {
    $locations = [
      'Lincoln',
      'Omaha',
      'Kearney'
    ];

    $status = [
      'Decommissioned',
      'Assigned'
    ];

    $values = [$status, $locations];

    foreach ($values as $value_list) {
      foreach ($value_list as $value_name) {
        $value = new Value();
        $value->name = $value_name;
        $value->save();
      }
    }

    $path = base_path('resources/JSON/types.json');
    $types = json_decode(file_get_contents($path), true);

    foreach ($types as $key => $values) {
      $type = new Type();
      $type->name = $key;
      $type->html_type = $values['HTMLType'];
      $type->sql_type = $values['SQLType'];
      $type->display_name = $values['displayName'];
      $type->protected = $values['protected'];
      $type->save();
    }

    $path = base_path('resources/JSON/columns.json');
    $columns_json = json_decode(file_get_contents($path), true);

    $columns = DB::getSchemaBuilder()->getColumnListing('items');

    foreach ($columns as $column) {
      $new_column = new Column();
      $new_column->name = $column;
      $new_column->display_name = $columns_json[$column]['displayName'];
      $new_column->display = $columns_json[$column]['display'];
      $new_column->protected = $columns_json[$column]['protected'];
      $new_column->required = $columns_json[$column]['required'];
      $type = Type::where('name', $columns_json[$column]['type'])->first();
      $new_column->type_id = $type->id;

      $new_column->save();

      if (array_key_exists('values', $columns_json[$column])) {
        $values = $columns_json[$column]['values'];
        foreach ($values as $value_name) {
          $value = Value::where('name', $value_name)->first();
          $new_column->values()->attach($value);
        }
        $new_column->save();
      }

    }

    // Add Three roles

    $admin_role = new Role();
    $admin_role->name = 'admin';
    $admin_role->id = 1;
    $admin_role->save();

    $normal_role = new Role();
    $normal_role->name = 'normal';
    $normal_role->id = 2;
    $normal_role->save();

    $guest_role = new Role();
    $guest_role->name = 'guest';
    $guest_role->id = 3;
    $guest_role->save();

    // Add default admin account

    $admin = new User();
    $admin->name = 'Admin';
    $admin->password = Hash::make("Administrator");
    $admin->email = 'admin@example.com';
    $admin->role_id = $admin_role->id;
    $admin->save();

    Log::debug('Database installer ran.');
  }

  /**
  * Execute the console command.
  *
  * @return mixed
  */
  public function handle()
  {
    try {
      $this->install();

      $this->info('The database installed successfully.');
    } catch (ProcessFailedException $exception) {
      $this->error('The database installer failed See Log for details.');
      Log::debug($exception);
    }
  }
}

return redirect("/items");
