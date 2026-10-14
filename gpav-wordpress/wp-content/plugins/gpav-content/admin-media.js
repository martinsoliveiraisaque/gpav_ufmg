jQuery(function ($) {
  $('#gpav-select-attachments').on('click', function () {
    const picker = wp.media({ title: 'Anexos / Attachments', multiple: true });
    picker.on('select', function () {
      $('#gpav_attachments').val(picker.state().get('selection').map(item => item.id).join(','));
    });
    picker.open();
  });
});
