$(document).ready(function () {
    $('#slider').flexslider({
        controlNav: false,
        directionNav: true,
    });

    $(function(){
        $('.fa-minus').click(function(){
            $(this).closest('.chatbox').toggleClass('chatbox-min');
        });
        $('.fa-close').click(function(){
            $(this).closest('.chatbox').hide();
        });
    });
    
    function toggleMenu () {
        const navbar = document.querySelector('.navbar');
        const burger = document.querySelector('.burger');
        burger.addEventListener('click', (e) => {
            navbar.classList.toggle('show-nav');
        });
    }
    toggleMenu();
})


var adminNs =
    {
        initDraggableEntityRows: function() {
            var dragSrcEl = null; // the object being drug
            var startPosition = null; // the index of the row element (0 through whatever)
            var endPosition = null; // the index of the row element being dropped on (0 through whatever)
            var parent; // the parent element of the dragged item
            var entityId; // the id (key) of the entity
            function handleDragStart(e) {
                dragSrcEl = this;
                entityId = $(this).attr('rel');
                dragSrcEl.style.opacity = '0.4';
                parent = dragSrcEl.parentNode;
                startPosition = Array.prototype.indexOf.call(parent.children, dragSrcEl);
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/html', this.innerHTML);
            }
            function handleDragOver(e) {
                if (e.preventDefault) {
                    e.preventDefault();
                }
                e.dataTransfer.dropEffect = 'move';
                return false;
            }
            function handleDragEnter(e) {
                this.classList.add('over');
            }
            function handleDragLeave(e) {
                this.classList.remove('over');
            }
            function handleDrop(e) {
                if (e.stopPropagation) {
                    e.stopPropagation();
                }
                if (dragSrcEl != this) {
                    endPosition = Array.prototype.indexOf.call(parent.children, this);
                    console.log("end: "+endPosition);
                    dragSrcEl.innerHTML = this.innerHTML;
                    this.innerHTML = e.dataTransfer.getData('text/html');
                    $.ajax({
                        url: '/admin/hero/sort/'+entityId+'/'+endPosition,
                    })
                        .done(function(res) {
                            $("table.sortable tbody").replaceWith($(res).find("table.sortable tbody"));
                        })
                        .fail(function(err) {
                            alert("An error occurred while sorting. Please refresh the page and try again.")
                        })
                        .always(function() {
                            adminNs.initDraggableEntityRows();
                        });
                }
                return false;
            }
            function handleDragEnd(e) {
                this.style.opacity = '1';
                [].forEach.call(rows, function (row) {
                    row.classList.remove('over');
                });
            }
            var rows = document.querySelectorAll('table.sortable > tbody tr');
            [].forEach.call(rows, function(row) {
                row.addEventListener('dragstart', handleDragStart, false);
                row.addEventListener('dragenter', handleDragEnter, false);
                row.addEventListener('dragover', handleDragOver, false);
                row.addEventListener('dragleave', handleDragLeave, false);
                row.addEventListener('drop', handleDrop, false);
                row.addEventListener('dragend', handleDragEnd, false);
            });
        },
        /**
         * Primary Admin initialization method.
         * @returns {boolean}
         */
        init: function() {
            this.initDraggableEntityRows();
            return true;
        }
    };
$(function() {
    adminNs.init();
});

