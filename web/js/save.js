($ => {
  // あらかじめ保存された払込先
  const presavedDest = [
    {
      name: '日本赤十字社東日本大震災義援金',
      mtime: null,
      data: {
        account1: '00140',
        account2: '8',
        account3: '507',
        account_name: '日本赤十字社 東日本大震災義援金',
      },
    },
    {
      name: '東日本大震災義援金政府窓口',
      mtime: null,
      data: {
        account1: '00130',
        account2: '6',
        account3: '623461',
        account_name: '東日本大震災義援金政府窓口',
      },
    },
    {
      name: '日本赤十字社平成30年7月豪雨災害義援金',
      mtime: null,
      data: {
        account1: '00130',
        account2: '8',
        account3: '635289',
        account_name: '日赤平成30年7月豪雨災害義援金',
      },
    },
  ];

  $(() => {
    const $modalSave = $('#modal-save');
    const $modalLoad = $('#modal-load');

    // 保存ダイアログを開いたとき、最初のフィールドにフォーカスを与える
    $modalSave.on('shown.bs.modal', () => {
      $('input[type="text"]', $modalSave).first().focus();
    });

    // button disabled を解除
    $('.saver').prop('disabled', false);

    // Save ボタンを押したときに必要なデータをダイアログにコピーし
    // ダイアログを開く
    $('.saver.saver-save').click(function () {
      const $this = $(this);
      const $input = $('input[name="name"]', $modalSave);
      $('.modal-title', $modalSave).text($this.data('label'));
      $input.data('save', $this.data('save')).val('');
      $modalSave.modal();
    });

    // Save ダイアログの Save ボタンを押したとき
    $('.btn-save', $modalSave).click(function () {
      const $input = $('input[name="name"]', $modalSave);
      const saveName = String($input.val()).trim();
      if (saveName.length < 1) {
        alert("保存名を入力してください");
        return;
      }

      const saveData = {
        name: saveName,
        mtime: Math.floor(Date.now() / 1000),
        data: {},
      };
      const datasetName = 'data-save-' + $input.data('save');
      $(`[${datasetName}]`).each(function () {
        const $this = $(this);
        if ($this.prop('tagName') !== 'select') {
          saveData.data[$this.attr(datasetName)] = $this.val();
        } else {
          saveData.data[$this.attr(datasetName)] = $('option:selected', $this).val();
        }
      });

      const internalName = 'save-' + $input.data('save') + '-' + Date.now();
      localStorage.setItem(internalName, JSON.stringify(saveData));

      $modalSave.modal('hide');
    });

    // Load ボタンを押したときに必要なデータをダイアログにコピーし
    // ダイアログを開く
    $('.saver.saver-load').click(function () {
      const $this = $(this);
      const saveKey = $this.data('save');
      const $select = $('select[name="target"]', $modalLoad);
      $('.modal-title', $modalLoad).text($this.data('label'));
      $select.data('save', saveKey).empty();
      const reqPrefix = `save-${saveKey}-`;
      for (let i = 0; i < localStorage.length; ++i) {
        const dataKey = localStorage.key(i);
        if (reqPrefix === dataKey.substr(0, reqPrefix.length)) {
          const json = JSON.parse(localStorage.getItem(dataKey));
          const mtime = (() => {
            const date = new Date();
            date.setTime(json.mtime * 1000);
            return date;
          })();
          $select.append(
            $('<option>')
              .attr('value', JSON.stringify(json.data))
              .attr('data-id', dataKey)
              .text(`${json.name} (${mtime.toLocaleDateString()})`)
          );
        }
      }

      // 払込先指定の読み込みではシステムによるデフォルトも設定する
      if (presavedDest.length && saveKey === 'to') {
        const $group = $('<optgroup label="システム">');
        presavedDest.forEach(current => {
          $group.append(
            $('<option>')
              .attr('value', JSON.stringify(current.data))
              .attr('data-id', '')
              .text(current.name)
          );
        });
        $select.append($group);
      }

      $modalLoad.modal();
    });

    // Load ダイアログの Load ボタンを押したとき
    $('.btn-load', $modalLoad).click(function () {
      const $select = $('select[name="target"]', $modalLoad);
      const $option = $('option:selected', $select);

      if ($option.length < 1) {
        alert('読込対象を指定してください\n（または×を押してキャンセルしてください）');
        return;
      }

      const json = JSON.parse($option.val());
      const datasetName = 'data-save-' + $select.data('save');
      $(`[${datasetName}]`).each(function () {
        const $this = $(this);
        const propName = $this.attr(datasetName);
        $this.val(json[propName]);
      });

      $modalLoad.modal('hide');
    });
  });
})(jQuery);
