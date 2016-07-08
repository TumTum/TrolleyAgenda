/**
 * Created by tobi on 08.05.16.
 */

function TrolleyAgenda() {}

TrolleyAgenda.prototype.run = function() {
    this.initMobileSpecials();
    this.tableHasNotHoverByMobileDevice();
    this.waitMeInit();
};

/**
 * Veränderungen die nur Mobile Versionen betrifft
 */
TrolleyAgenda.prototype.initMobileSpecials = function () {
    if ((/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) )) {
        this.fastclick_enable();
        this.tableHasNotHoverByMobileDevice();
    }
};

/**
 * Auf dem Mobile geräte ist es hinderlich dieses Table-Hover Deshalb wird es enfernt
 */
TrolleyAgenda.prototype.tableHasNotHoverByMobileDevice = function() {
    $('table').removeClass("table-hover");
};

/**
 * Die Delay zeiten von den Browser verhindern um das gefühl der geschwindig keit zu bekommen
 */
TrolleyAgenda.prototype.fastclick_enable = function() {
    FastClick.attach(document.body);
};

 * Um die warte Zeit zu überbrücken bis der Server reagiert.
 *
 * Bei alle Formulare automatisch und Knöpfe mit der der Classe .waitRequest
 */
TrolleyAgenda.prototype.waitMeInit = function() {
    var run_wait = function(element) {
        if (element == undefined) {
            element = $("body");
        }

        element.waitMe({
            effect: 'pulse',
            text: 'Bitte warten...',
            bg: 'rgba(0,0,0,0.6)',
            color: '#ffffff'
        });
    };

    $("form").submit(function(event) { run_wait() });
    $(".waitRequest").click(function(event) { run_wait() })
};

$(function(){
    var trolleyAgenda = new TrolleyAgenda();
    trolleyAgenda.run();
});
