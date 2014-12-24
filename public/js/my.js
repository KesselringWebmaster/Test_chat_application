(function () {
    $('#listmessages').scrollTop(90000);
    /*Scrolling to bottom of the chat div with JQuery*/

    var form = document.getElementById('chatForm') || null;
    if (form == null)
        return false;
    /*form is not found*/

    /*setting necessary option to the form*/
    form.action = 'javascript:void(0);';

    /*regestering onsumbit event*/
    form.onsubmit = function () {
        var kvpairs = [];
        for (var i = 0; i < this.elements.length; i++) {
            var e = this.elements[i];
            if (e.name) {
                kvpairs.push(encodeURIComponent(e.name) + "=" + encodeURIComponent(e.value));
            }
        }
        kvpairs.push('ajax=true');
        var data = kvpairs.join("&");
        sendAjax(data);
        /*sending Ajax request*/
    }

    /**
     * Sending XmlHttpRequest
     * @param data
     */
    function sendAjax(data) {
        var xmlhttp;
        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                location.reload();
            }
        }
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(data);
    }
})();
/*Calling the function right away*/