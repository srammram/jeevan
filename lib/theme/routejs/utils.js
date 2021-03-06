define(["jquery", "knockout", "datepicker"], function ($, ko) {

//    self.targetDate = ko.observable('01/10/2015');
//    self.datePickerMinDate = ko.observable('01/04/2017');

    ko.bindingHandlers.fade = {
        init: function (element, valueAccessor) {
            var value = ko.utils.unwrapObservable(valueAccessor());
            $(element).toggle(value);
        },
        update: function (element, valueAccessor) {
            ko.utils.unwrapObservable(valueAccessor()) ? $(element).fadeIn() : $(element).fadeOut();
        }
    }

//    ko.bindingHandlers.datepicker = {
//        init: function (element, valueAccessor, allBindingsAccessor) {
//            //initialize datepicker with some optional options
//            var options = allBindingsAccessor().datepickerOptions || {};
//            $(element).datepicker(options);
//            //handle the field changing
//            ko.utils.registerEventHandler(element, "change", function () {
//                var observable = valueAccessor();
//                observable($(element).datepicker("getDate"));
//            });
//            //handle disposal (if KO removes by the template binding)
//            ko.utils.domNodeDisposal.addDisposeCallback(element, function () {
//                $(element).datepicker("destroy");
//            });
//        },
//        //update the control when the view model changes
//        update: function (element, valueAccessor) {
//            var value = ko.utils.unwrapObservable(valueAccessor()),
//                    current = $(element).datepicker("getDate");
//            if (value - current !== 0) {
//                $(element).datepicker("setDate", value);
//            }
//        }
//    };
//    ko.bindingHandlers.datepicker = {
//        init: function (element, valueAccessor, allBindingsAccessor) {
//            //initialize datepicker with some optional options
//            var options = allBindingsAccessor().datetimepickerOptions || {format: 'yyyy-mm-dd'};
//            $(element).datepicker(options);
//
//            //when a user changes the date, update the view model
//            ko.utils.registerEventHandler(element, "changeDate", function (event) {
//                var value = valueAccessor();
//                if (ko.isObservable(value)) {
//                    value(event.date);
//                }
//            });
//        },
//        update: function (element, valueAccessor) {
//            var widget = $(element).data("datepicker");
//            //when the view model is updated, update the widget
//            if (widget) {
//                widget.date = ko.utils.unwrapObservable(valueAccessor());
//                widget.setValue();
//            }
//            if (String(widget).indexOf('/Date(') == 0) {
//                widget = new Date(parseInt(widget.replace(/\/Date\((.*?)\)\//gi, "$1")));
//            }
//        }
//    };
//    
    ko.bindingHandlers.datepicker = {
        init: function (element, valueAccessor, allBindingsAccessor) {
            //initialize datepicker with some optional options
            var options = allBindingsAccessor().datepickerOptions || {},
                    observable = valueAccessor(),
                    $el = $(element);

            options.onSelect = function () {
                if (ko.isObservable(observable)) {
                    observable($el.datepicker('getDate'));
                }
            };

            $el.datepicker(options);

            // set the initial value
            var value = ko.unwrap(valueAccessor());
            if (value) {
                $el.datepicker("setDate", value);
            }

            //handle disposal (if KO removes by the template binding)
            ko.utils.domNodeDisposal.addDisposeCallback(element, function () {
                $el.datepicker("destroy");
            });
        },
        update: function (element, valueAccessor) {
            var value = ko.utils.unwrapObservable(valueAccessor()),
                    $el = $(element);

            //handle date data coming via json from Microsoft
            if (String(value).indexOf('/Date(') === 0) {
                value = new Date(parseInt(value.replace(/\/Date\((.*?)\)\//gi, "$1")));
            }

            var current = $el.datepicker("getDate");

            if (value - current !== 0) {
                $el.datepicker("setDate", value);
            }
        }
    };
//    var viewModel = {
//        myDate: ko.observable(new Date("12/01/2013")),
//        setToCurrentDate: function () {
//            this.myDate(new Date());
//        },
//    };

});