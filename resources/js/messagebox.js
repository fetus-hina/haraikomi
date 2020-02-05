// Copyright (C) AIZAWA Hina | MIT License
(window => {
  function msgbox(text, title, icon) {
    const $modal = $('#message-box');

    // アイコンの調整
    const icons = {
      info: 'modal-icon-info',
      warning: 'modal-icon-warning',
      error: 'modal-icon-error',
    };
    for (const [iconType, iconClass] of Object.entries(icons)) {
      const $icon = $(`.${iconClass}`, $modal);
      if (icon === iconType) {
        $icon.removeClass('d-none');
      } else if (!$icon.hasClass('d-none')) {
        $icon.addClass('d-none');
      }
    }

    $('.modal-title', $modal).text(title || 'お知らせ');
    $('#message-box-content', $modal).text(text);

    $modal.modal();
  }

  window.msgboxInfo = (text, title) => {
    return msgbox(text, title, 'info');
  };

  window.msgboxWarn = (text, title) => {
    return msgbox(text, title, 'warning');
  };

  window.msgboxWarning = window.msgboxWarn;

  window.msgboxError = (text, title) => {
    return msgbox(text, title, 'error');
  };
})(window);
