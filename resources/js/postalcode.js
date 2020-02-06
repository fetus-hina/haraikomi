($ => {
  function update(entry, updateMap) {
    for (const [selectorTarget, entryIndex] of Object.entries(updateMap)) {
      const $obj = $(selectorTarget);
      if ($obj.length) {
        $obj.val(entryIndex ? entry[entryIndex] : '');
      }
    }
  }

  $.fn.postalcode = function (selectorInput, selectorChoiceDialog, updateMap) {
    const $buttons = this;
    const $inputs = $(selectorInput);

    // 数字7桁のときだけボタン有効化 {{{
    let timerId = null;
    const updateEnableDisableButton = () => {
      if (timerId) {
        clearTimeout(timerId);
        timerId = null;
      }
      timerId = setTimeout(
        () => {
          timerId = null;
          $buttons.prop(
            'disabled',
            !String($inputs.val()).match(/^\d{7}$/)
          );
        },
        1000 / 60
      );
    };
    $inputs
      .keypress(() => {
        updateEnableDisableButton();
      })
      .change(() => {
        updateEnableDisableButton();
      });
    updateEnableDisableButton();
    // }}}

    this.each(function () {
      const $button = $(this);
      $button.click(() => {
        $button.text('問合せ中...');
        $.post('/api/postal-code', {code: $inputs.val()})
          .done(data => {
            switch (data.length) {
              case 0:
                window.msgboxWarn('郵便番号の検索結果が空でした');
                break;

              case 1:
                update(data[0], updateMap);
                break;

              default:
                (() => {
                  const $choiceDialog = $(selectorChoiceDialog);
                  const $choiceList = $('.list-group', $choiceDialog).empty();
                  data.forEach(row => {
                    const $item = $('<a href="#" class="list-group-item list-group-item-action">');
                    $item.text(`${row.address1} ${row.address2} ${row.address3}`);
                    $choiceList.append($item);
                    $item.click(() => {
                      update(row, updateMap);
                      $choiceDialog.modal('hide');
                      return false;
                    });
                  });
                  $choiceDialog.modal();
                })();
                break;
            }
          })
          .fail(() => {
            window.msgboxError('検索エラーが発生しました');
          })
          .always(() => {
            $button.text('住所入力')
          });
      });
    });
    return this;
  };
})(jQuery);
