
/**
* First we will load all of this project's JavaScript dependencies which
* includes Vue and other libraries. It is a great starting point when
* building robust, powerful web applications using Vue and Laravel.
*/

require('./bootstrap');

window.Vue = require('vue');

/**
* The following block of code may be used to automatically register your
* Vue components. It will recursively scan this directory for the Vue
* components and automatically register them with their "basename".
*
* Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
*/

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
* Next, we will create a fresh Vue application instance and attach it to
* the page. Then, you may begin adding components to this application
* or customize the JavaScript scaffolding to fit your unique needs.
*/

const app = new Vue({
  el: '#app',
});

$(document).ready(function(){


  $('#search').keydown(function (e) {
    if(e.keyCode === 13) {
      $('#new-search').val('1');
    }
  });

  $('.search-wrapper > .button').click(function (e) {
      $('#new-search').val('1');
  });

  $('.is-bottom-right').css('right', '1em').delay(10000).queue(function(nxt) {
    $(this).css('right', '-100%');
    nxt();
  });

  $('.add-value').click(function () {
    const input = '<input type="text" class="input" name="value-names[]" value="" placeholder="New Value..." required>';
    const button = '<button type="button" class="button is-danger remove-value">Remove Value</button>';
    const value = document.createElement("div");
    $(value).addClass('value');
    $(value).append(input);
    $(value).append(button);
    $('.values').append(value);
    bindRemoveValueButtons();
  })

  function bindRemoveValueButtons () {
    $('.remove-value').click(function () {
      $(this).parent().remove();
    })
  }

  bindRemoveValueButtons();

  $('.button[name=add-or]').click( function () {

    const label = $(this).parent().siblings('.label');

    if (label.children('.bound-group').length) {
      const newBoundGroup = label.children('.bound-group').last().clone();
      const oldID = newBoundGroup.attr('id');
      const regex = /bound-[0-9]/;
        const count = Number(oldID.slice(-1)) + 1;
        const newID = oldID.substring(0, oldID.length-1) + count;
      newBoundGroup.attr('id', newID);
      newBoundGroup.children('input').each(function () {
        const oldName = $(this).attr('name');
        const newName = oldName.replace(oldID, newID);
        $(this).attr('name', newName);
      });
      newBoundGroup.insertAfter(label.children('.bound-group').last());
    } else {
      const newInput = label.children('input').last().clone();
      newInput.insertAfter(label.children('.input').last());
    }
  });

  $('.button[name=remove-or]').click( function () {
    const label = $(this).parent().siblings('.label');
    if (label.children('.bound-group').length > 1) {
      const newBoundGroup = label.children('.bound-group').last().remove();
    } else if (label.children('input').length > 1) {
      const newInput = label.children('input').last().remove();
    }
  });


  $('th').click(function () {
    const form = $('.form');

    const sortBy = $(this).attr('class');
    form.find('#sort-by').val(sortBy);

    const sortDirection = form.find('#sort-direction').val();
    const flippedDirection = sortDirection === 'ASC' ? 'DESC' : 'ASC';

    $('i.fa-angle-double-down').addClass('disabled');
    $('i.fa-angle-double-up').addClass('disabled');

    if (flippedDirection === 'ASC') {
      $(this).find('i.fa-angle-double-up').removeClass('disabled');
    } else {
      $(this).find('i.fa-angle-double-down').removeClass('disabled');
    }
    form.find('#sort-direction').val(flippedDirection);


    form.submit();

  });

  $('#paginate-count').on('change', function() {
    this.form.submit();
  });

  fixTableStripes();

  // Fix Table Striping while searching or sorting
  function fixTableStripes() {
    $("tbody tr:not(:hidden)").each(function (index) {
      $(this).toggleClass("stripe", !!(index+1 & 1));
    });
  }


  // Change the text under 'file name' on upload screens

  $('.file').on('change', function () {

    if($(this).find('.file-input')[0].files.length > 0)
    {
      $(this).find('.file-name')[0].innerHTML = $(this).find('.file-input')[0].files[0].name;
    }
  });

  // Select All Button
  $('.button[name="select-all"]').click(function() {
    $('.select-item').each(function () {
      if ($(this).closest('tr').is(':visible')){
        $(this).prop('checked', true);
      } else if (!$(this).closest('tr')[0]) {
        $(this).prop('checked', true);
      };
      $(this).closest('.field').removeClass('disabled');
    });
    updateSelectedItems();
  });

  function updateSelectedItems() {
    let selectedCount = $('.select-item:checked').length
    let counter = $('#selected-count')[0];
    if (counter) {
      counter.innerHTML = selectedCount;
    }
  };

  // Unselect All Button
  $('.button[name="clear-all"]').click(function() {
    $('.select-item').each(function () {
      $(this).prop('checked', false);
      $(this).closest('.field').addClass('disabled');
    });
    updateSelectedItems();
  });

  $('.select-item').click(function () {
    if (!$(this).is(':checked')) {
      $(this).closest('.field').addClass('disabled');
    } else {
      $(this).closest('.field').removeClass('disabled');
    }
    updateSelectedItems();
  });

});
