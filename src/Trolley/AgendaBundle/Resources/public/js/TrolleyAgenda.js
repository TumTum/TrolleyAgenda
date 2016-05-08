/**
 * Created by tobi on 08.05.16.
 */

function TrolleyAgenda() {}

TrolleyAgenda.prototype.run = function() {
    this.tableHasNotHoverByMobileDevice();
};

TrolleyAgenda.prototype.tableHasNotHoverByMobileDevice = function() {
    if ((/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) )) {
        $('table').removeClass("table-hover");
    }
};

$(function(){
    var trolleyAgenda = new TrolleyAgenda();
    trolleyAgenda.run();
});
