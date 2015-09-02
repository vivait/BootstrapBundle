var vivaBootstrap = function () {
    var $d = $(document);

    // Executed once on the document load
    this.documentLoaded = function () {
        this.registerSaveTab();
        this.registerDialog2();
        this.registerConfirmation();
        this.registerCheckbox();
        this.registerSidebar();
        this.registerMessenger();
        this.registerDownloadFile();

        this.contentLoaded();
    };

    // Executed each time the content has changed
    this.contentLoaded = function (content) {
        if (!content) {
            content = $d;
        }

        this.registerPolyCollections(content);
        this.restoreTab(content);
        this.registerDatepicker(content);
    };

    this.registerDatepicker = function (content) {
        // Datepicker configuration
        $('[data-provider="datepicker"]', content).datetimepicker({
            autoclose: true,
            format: 'dd/mm/yyyy',
            language: 'fr',
            minView: 'month',
            pickerPosition: 'bottom-left',
            todayBtn: true,
            startView: 'month'
        });

        $('[data-provider="datetimepicker"]', content).datetimepicker({
            autoclose: true,
            format: 'dd/mm/yyyy hh:ii',
            language: 'fr',
            pickerPosition: 'bottom-left',
            todayBtn: true
        });

        $('[data-provider="timepicker"]', content).datetimepicker({
            autoclose: true,
            format: 'hh:ii',
            formatViewType: 'time',
            maxView: 'day',
            minView: 'hour',
            pickerPosition: 'bottom-left',
            startView: 'day'
        });

        // Restore value from hidden input
        $('.date input[type=hidden]', content).each(function () {
            if ($(this).val()) {
                $(this).parent().datetimepicker('setValue');
            }
        });
    }

    this.registerSaveTab = function () {
        $d.on('show.bs.tab', 'a[data-toggle="tab"]', function (e) {
            //save the latest tab; use cookies if you like 'em better:
            localStorage.setItem('lastTab', $(e.target).attr('href'));
        });
    };

    this.registerDialog2 = function () {
        $d.on("click", '.ajax-dialog', function (event) {
            $('#modalDialog').dialog2({
                initialLoadText: "One moment...",
                id: "modal-dialog",
                content: this.href,
                closeOnOverlayClick: false,
                closeOnEscape: true,
                removeOnClose: true,
                showCloseHandle: true
            });
            event.preventDefault();
        });

        $d.on("dialog2.content-update", ".modal", function () {
            // Update the content
            vivaBootstrap.contentLoaded();

            // got the dialog as this object. Do something with it!
            var e = $(this);
            var autoclose = e.find("a.auto-close");
            if (autoclose.length > 0) {
                e.hide();
                var href = autoclose.attr('href');
                if (href) {
                    window.location.href = href;
                }
            }
        });
    }

    this.registerConfirmation = function () {
        $d.on("click", '.confirm-message', function (e) {
            var cnfm = confirm($(this).data('confirm-message'));
            if (!cnfm) {
                e.stopImmediatePropagation();
            }
            return cnfm;
        });
    };

    this.registerCheckbox = function () {
        $d.on("click", '.btn-group2 .btn', function () {
            hidden = document.getElementById($(this).data("hiddenid"));
            if ($(this).hasClass('active')) {
                hidden.name = 'nobody';
            } else {
                hidden.name = $(this).data("hiddenname");
            }
        });
    };

    this.registerSidebar = function () {
        $d.on("click", "#sidebar-activate", function () {
            $('#sidebar').toggleClass('active')
        });
    };

    this.registerMessenger = function () {
        Messenger.options = {
            extraClasses: 'messenger-fixed messenger-on-bottom messenger-on-right',
            theme: 'future'
        }
    };

    this.registerDownloadFile = function () {
        window.downloadFile = function (sUrl) {

            //If in Chrome or Safari - download via virtual link click
            if (window.downloadFile.isChrome || window.downloadFile.isSafari) {
                //Creating new link node.
                var link = document.createElement('a');
                link.href = sUrl;

                if (link.download !== undefined) {
                    //Set HTML5 download attribute. This will prevent file from opening if supported.
                    var fileName = sUrl.substring(sUrl.lastIndexOf('/') + 1, sUrl.length);
                    link.download = fileName;
                }

                //Dispatching click event.
                if (document.createEvent) {
                    var e = document.createEvent('MouseEvents');
                    e.initEvent('click', true, true);
                    link.dispatchEvent(e);
                    return true;
                }
            }

            // Force file download (whether supported by server).
            var query = '?download';

            window.open(sUrl + query, '_self');
        }

        window.downloadFile.isChrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
        window.downloadFile.isSafari = navigator.userAgent.toLowerCase().indexOf('safari') > -1;
    };

    this.registerPolyCollections = function (content) {
        $('[data-collection-add-btn]', content).each(function () {
            new window.infinite.Collection($($(this).data('collection-add-btn')), $(this));
        });
    }

    this.restoreTab = function (content) {
        // Go to the latest tab, if it exists:
        var lastTab = localStorage.getItem('lastTab'),
            tab = $('a[href="' + lastTab + '"]', content);

        if (lastTab && tab.length && !tab.parent('li').hasClass('active')) {
            tab.tab('show');
        }
        else {
            content.find('li.active a[data-toggle="tab"]').trigger('shown.bs.tab');
        }
    };

    return this;
}();
