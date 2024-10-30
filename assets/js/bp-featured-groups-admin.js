jQuery(document).ready(function($){

    $(document).on('change','.bpfg-widget-admin-widget-view-options', function ( $el ) {
        var $this = $(this);
        var $options = $this.parents( '.widget-inside' ).find('.bpfg-widget-admin-widget-slide-options');
        if ( 'slider' == $this.val() ) {
            $options.show();
        } else {
            $options.hide();
        }
    });

});