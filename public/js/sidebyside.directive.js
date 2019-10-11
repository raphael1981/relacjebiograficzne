            angular.module("app")
                    .directive("customItem", function () {
                        return {
                            scope: {
                                item: "=",
                                onSelect: "&",
                                isSelected: "&"
                            },
                            template: "<div class='custom' ng-click=\"onSelect({item: item})\">" +
                            "<span ng-show=\"isSelected({item: item})\">*</span>" +
                            "{{item.name}}" +
                            "</div>"
                        }
                    })
					
/*					
					.directive("customItem2", function () {
                        return {
                            scope: {
                                item: "=",
                                onSelect: "&",
                                isSelected: "&"
                            },
                            template: "<div class='custom' ng-click=\"onSelect({item: item})\">" +
                            "<span ng-show=\"isSelected({item: item})\">Selected</span>" +
                            "second directive {{item.name}}" +
                            "</div>"
                        }
                    }).directive("customItem3", function () {
                        return {
                            scope: {
                                item: "=",
                                onSelect: "&",
                                isSelected: "&"
                            },
                            template: "<div class='custom' ng-click=\"onSelect({item: item})\">" +
                            "<span ng-show=\"isSelected({item: item})\">Selected</span>" +
                            "third directive {{item.name}}" +
                            "</div>"
                        }
                    });
*/				