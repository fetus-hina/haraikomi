/*! Copyright (C) AIZAWA Hina | MIT License */
jQuery($ => {
  const $modalLoad = $('#modal-load');

  // button disabled を解除
  $('.gienkin').prop('disabled', false);

  // Load ボタンを押したときに必要なデータをダイアログにコピーし
  // ダイアログを開く
  $('.gienkin.gienkin-load').click(function () {
    const $this = $(this);
    const $select = $('select[name="target"]', $modalLoad).data('save', 'to').empty();
    $('.preset-notice', $modalLoad).removeClass('d-none');
    $('.modal-title', $modalLoad).text($this.data('label'));

    const presetData = JSON.parse($($this.data('preset')).text());
    presetData.forEach(groupInfo => {
      const $group = $('<optgroup>').attr('label', groupInfo.name);
      groupInfo.presets.forEach(current => {
        $group.append(
          $('<option>')
            .attr('value', JSON.stringify(current.data))
            .attr('data-id', '')
            .text(current.name)
        );
      });
      $select.append($group);
    });

    (new bootstrap.Modal($modalLoad.get(0))).show();
  });
});
