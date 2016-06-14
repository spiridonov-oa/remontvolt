/**
 * @package Helix3 Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2015 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
jQuery(function ($) {
    var $window = $(window);
    $(document).ready(function () {
        var $carouselFixed = $('.carousel.fixed');
        if ($carouselFixed.length) {
            var cbc = {
                init: function ($carouselNode) {
                    this.$carouselNode = $carouselNode;
                    this.imageWidthIndex = this.calculateImageWidthIndex();
                    this.$carouselWrapper = this.getCarouselWrapper();
                    this.isFixed = true;
                    this.imageHolderClassName = 'cbc-image-holder';
                    this.imageDataClassName = 'data-image';
                    this.$caption = $('.cbc-caption');

                    this.addWindowListeners();
                    this.setImages();

                    this.putCarouselToDom(this.$carouselNode);

                    addClassOnScroll('scrolling', $window.height());
                },

                addWindowListeners: function () {
                    var that = this;
                    $window.on('resize', function () {
                        that.recalculateCarouselHeight();
                        if (that.isImageIndexChanged()) {
                            that.setImages();
                        }
                    });
                    $window.scroll(function () {
                        that.moveImageOnScroll();
                    });
                },

                moveImageOnScroll: function () {
                    if (this.isFixed) {
                        var topPosition = -$window.scrollTop() / 4;
                        if ($window.scrollTop() > 0) {
                            if (topPosition > -$window.height()) {
                                var opacity = ($window.height()-$window.scrollTop()*2+300)/$window.height();
                                this.$caption.css({'top': topPosition + 'px'});
                                this.$carouselNode.css({'opacity': opacity});
                            }
                        } else if (this.$caption.css('top') !== '0px' ) {
                            this.$caption.css({'top': 0});
                            this.$carouselNode.css({'opacity': 1});
                        }
                    }
                },

                setImageWidthIndex: function (index) {
                    this.imageWidthIndex = index;
                },

                calculateImageWidthIndex: function () {
                    var width;
                    var widthValues;
                    var imageIndex = 0;
                    if (this.isVertical()) {
                        widthValues = [768, 400];
                    } else {
                        widthValues = [1920, 1366, 800];
                    }
                    width = widthValues[0];
                    widthValues.forEach(function (value, i) {
                        if ($window.width() <= value) {
                            width = value;
                            imageIndex = i;
                        } else {
                            return false;
                        }
                    });
                    return imageIndex;
                },

                isImageIndexChanged: function () {
                    return this.imageWidthIndex !== this.calculateImageWidthIndex();
                },

                setImages: function () {
                    var that = this;
                    var imageIndex = this.calculateImageWidthIndex();

                    this.setImageWidthIndex(imageIndex);
                    var orientation = '';
                    var $carouselInner = $('.' + this.imageHolderClassName, this.$carouselNode);
                    var $imageList = $('<div></div>');

                    if (this.isVertical()) {
                        orientation = '-vertical';
                    }

                    $('.' + this.imageDataClassName).each(function (index, imageData) {
                        var itemClass = 'item';
                        if (index === 0) {
                            itemClass = itemClass + ' active';
                        }
                        var imageName = $(imageData).hide().attr('data-image-src');
                        var src = imageName + orientation + '-' + imageIndex + '.jpg';
                        var $item = $('<div></div>').addClass(itemClass);
                        var $image = $('<img >').attr('src', src);

                        $item.append($image);
                        $imageList.append($item);
                    });

                    $carouselInner.replaceWith($imageList.addClass(this.imageHolderClassName));
                    $imageList.find('img').load(function () {
                        that.recalculateCarouselHeight();
                    });
                    return $imageList;
                },

                getCarouselWrapper: function () {
                    return this.$carouselNode.parent().add('carousel-wrapper');
                },

                putCarouselToDom: function ($carouselNode) {
                    this.$carouselWrapper.append($carouselNode);
                },
                alignItemsVerticalCenter: function ($items, height) {
                    height = height || $window.height();

                    var isMargin = !this.isVertical() || !this.isMobile();
                    $items.each(function (i, item) {
                        var $item = $(item);
                        var top = (height - $item.height()) / 2;
                        if (isMargin) {
                            $item.animate({marginTop: top + 'px', opacity: 1}, 300);
                        } else {
                            $item.animate({marginTop: 0, opacity: 1});
                        }
                        $item.addClass('visible');

                        var $h1 = $('h1', $item.parents('#cbc-carousel'));
                        var paddingTop;
                        if (isMargin) {
                            paddingTop = top / 2 + $item.height() / 7;
                            $h1.animate({paddingTop: paddingTop + 'px', opacity: 1}, 300);
                        } else {
                            paddingTop = $item.height() / 7;
                            $h1.animate({paddingTop: paddingTop, opacity: 1});
                        }
                    });
                },
                getWindowProportions: function () {
                    return $window.height() / $window.width();
                },
                getCarouselProportions: function () {
                    var windowsProportions = this.getWindowProportions();
                    var carouselProportions;
                    var proportions = [2 / 3, 8 / 16];
                    if (windowsProportions > proportions[0]) {
                        carouselProportions = proportions[0];
                    } else if (windowsProportions < proportions[1]) {
                        this.isFixed = false;
                        carouselProportions = proportions[1];
                    } else {
                        carouselProportions = windowsProportions;
                    }
                    return carouselProportions;
                },
                isVertical: function () {
                    return this.getWindowProportions() > 1 || $window.width() < 480;
                },
                isMobile: function () {
                    return $window.width() < 800;
                },
                getHeightForVertical: function () {
                    return "auto";
                },
                getHeightForHorizontal: function () {
                    return $window.width() * this.getCarouselProportions();
                },
                addClasses: function () {
                    var $node = this.$carouselNode;
                    this.isFixed ? $node.addClass('fixed') : $node.removeClass('fixed');
                    this.isVertical() ? $node.addClass('vertical') : $node.removeClass('vertical');
                    this.isMobile() ? $node.addClass('mobile') : $node.removeClass('mobile');
                },
                recalculateCarouselHeight: function () {
                    var height;

                    if (this.isVertical()) {
                        this.isFixed = false;
                        height = this.getHeightForVertical();
                    } else if (this.isMobile()) {
                        this.isFixed = false;
                        height = this.getHeightForVertical();
                    } else {
                        this.isFixed = true;
                        height = this.getHeightForHorizontal();
                    }
                    this.$carouselNode.height(height);
                    this.$carouselWrapper.height(height);
                    this.addClasses();
                    this.alignItemsVerticalCenter($('.item', this.$carouselNode), height);
                }
            };
            cbc.init($carouselFixed);
        }
    });


    //add Class on scroll
    function addClassOnScroll(className, positionTop, onElement) {
        onElement = onElement || 'body';
        $window.scroll(function () {
            if ($window.scrollTop() > positionTop) {
                $(onElement).addClass(className);
            } else {
                $(onElement).removeClass(className);
            }
        });
    }

    //Add scroll to section on button click
    function addScrollToSection(buttonSelector, sectionSelector) {
        // Set buttons listeners for scroll to section
        $(buttonSelector).live('click tap', function (event) {
            scrollToSection(event, sectionSelector);
        });

        function scrollToSection(event, sectionId) {
            event.preventDefault();
            event.stopPropagation();

            var positionOfCallbackSection = $(sectionId).offset().top;

            var speed = Math.abs($('body').scrollTop() - positionOfCallbackSection) / 5;
            $('html, body').animate({
                scrollTop: positionOfCallbackSection
            }, speed);
        }
    }

    //Set padding to Callback section to make it full screen
    function setPaddingToCallback() {
        var $callback = $('#sp-callback');
        var $header = $('#sp-header');
        var $callbackContent = $('.container', $callback);
        var top = ($window.height() - $callbackContent.height() - $header.height()) / 2;
        $callback.css({'paddingTop': top, 'paddingBottom': top});
    }

    //Copy telephone numbers
    function copyContentFromNode(telSourceClassName, telHolderClassName) {
        var $telSourceClassName = $(telSourceClassName);
        if ($telSourceClassName.length) {
            var $telHolderClassName = $(telHolderClassName);
            $telSourceClassName.children().clone().appendTo($telHolderClassName);
        }
        $telSourceClassName.remove();
    }

    function copyClassFromContentToBody(className) {
        var $nodeWithClass = $('#sp-main-body article.item-page.' + className);
        if ($nodeWithClass.length) {
            $('body').addClass(className);
        }
    }

    function insertNodes() {
        var insertElementData = 'data-insert-element';
        var insertHolderData = 'data-insert-holder';
        var $childNodes = $('[' + insertElementData + ']');
        var toInsert = {};
        if ($childNodes.length) {
            $childNodes.each(function (i, node) {
                var parentName = $(node).attr(insertElementData);
                if (!toInsert[parentName]) {
                    toInsert[parentName] = [];
                }
                toInsert[parentName].push($(node).clone());
            });
            for (var insertToElements in toInsert) {
                $('[' + insertHolderData + '="' + insertToElements + '"]').append(toInsert[insertToElements]);
            }
        }
    }

    function activateOffcanvasMenu () {
        var offcanvasMenu = $('.offcanvas-menu');
        var menu = $('.menu', offcanvasMenu);
        var items = $('li', menu);
        items.each(function (i, item) {
            var $item = $(item);
            if($item.hasClass('deeper')) {
                if($item.hasClass('active')) {
                    $item.addClass('opened');
                }
                var $arrow = $('<div></div>').addClass('offcanvas-expander');
                $item.append($arrow);
                $arrow.on('click', function (event) {
                    event.stopPropagation();
                    $item.toggleClass('opened');
                });
            }
        });
    }

    function addRandomComments() {
        var randomComments = {
            init: function () {
                this.$commentsList = $('.comments-list').detach();
                this.$comments = $('.comments');
                if (!this.$commentsList.length) {
                    return null;
                }
                var options = {
                    commentsOnPage: 2,
                    commentsInRow: 2
                };
                var comments = this.getComments(this.$commentsList);
                this.randomComments = this.shuffle(comments);
                this.commentsToShow = this.getCommentsToShow(this.randomComments, options);
                this.showComments(this.commentsToShow, options);
            },

            getComments: function (commentsList) {
                return $('.comment', commentsList);
            },

            getCommentsToShow: function (randomComments, options) {
                var numberCommentsToShow = options.commentsOnPage;
                if (numberCommentsToShow > randomComments.length) {
                    numberCommentsToShow = randomComments.length;
                }
                return randomComments.slice(0, numberCommentsToShow);
            },

            showComments: function (commentsToShow, options) {
                if (!commentsToShow.length) {
                    return null;
                }
                var $list = $('<div>');
                var commentsCount = commentsToShow.length;
                var commentsInRow = options.commentsInRow;
                var numberOfRows = commentsCount / commentsInRow;
                if (numberOfRows < 1) {
                    numberOfRows = 1;
                }

                for (var i = 0; i < numberOfRows; i++) {
                    if (!commentsToShow.length) {
                        break;
                    }
                    var $row = $('<div>').addClass('row');
                    for (var j = 0; j < commentsInRow; j++) {
                        if (!commentsToShow.length) {
                            break;
                        }
                        var number = (i + 1) * (j + 1) - 1;
                        $row.append(commentsToShow[number]);
                    }
                    $list.append($row);
                }

                this.$comments.append($list);
            },

            shuffle: function (array) {
                var currentIndex = array.length, temporaryValue, randomIndex;

                // While there remain elements to shuffle...
                while (0 !== currentIndex) {

                    // Pick a remaining element...
                    randomIndex = Math.floor(Math.random() * currentIndex);
                    currentIndex -= 1;

                    // And swap it with the current element.
                    temporaryValue = array[currentIndex];
                    array[currentIndex] = array[randomIndex];
                    array[randomIndex] = temporaryValue;
                }

                return array;
            }
        };
        randomComments.init();
    }

    $window.resize(function () {
        setPaddingToCallback();
    });

    //init all scripts on document ready

    $(document).ready(function () {
        setPaddingToCallback();
        addScrollToSection('.btn-callback', '#sp-callback');
        addScrollToSection('.btn-price', '#sp-price-list');
        copyContentFromNode('.telephones-list-source', '.telephones-list');
        copyContentFromNode('.action-buttons-source', '.action-buttons');
        addRandomComments();
        activateOffcanvasMenu();
        copyClassFromContentToBody('landing-page');
        copyClassFromContentToBody('contacts');
        insertNodes();
    });
});