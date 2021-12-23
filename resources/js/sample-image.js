($ => {
  $.fn.sampleImage = function () {
    this.hover(
      function () {
        $(this).attr('src', $(this).data('hover'));
      },
      function () {
        $(this).attr('src', $(this).data('original'));
      }
    );
    return this;
  };
})(jQuery);
