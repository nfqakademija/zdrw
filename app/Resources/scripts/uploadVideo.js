/*jshint unused:false*/
'use strict';
function _(el){
    return document.getElementById(el);
}
function progressHandler(event){
    _('loaded_n_total').innerHTML = 'Uploaded '+event.loaded+' bytes of '+event.total;
    var percent = (event.loaded / event.total) * 100;
    $('#progressBar').css('width', Math.round(percent)+'%');
    _('status').innerHTML = Math.round(percent)+'% uploaded... please wait';
}
function completeHandler(event){
    _('status').innerHTML = event.target.responseText;
    _('progressBar').value = 0;
    location.reload();
}
function errorHandler(){
    _('status').innerHTML = 'Upload Failed';
}
function abortHandler(){
    _('status').innerHTML = 'Upload Aborted';
}
function uploadFile(ajaxlink){
    var file = _('file1').files[0];
    var form = $('#upload_form');
    var id = form.find('input[name="id"]').val();
    var userid = form.find('input[name="userid"]').val();
    var formdata = new FormData();
    if (typeof file !== 'undefined') {
        if (file.type.match('video.*')) {
            formdata.append('file1', file);
            formdata.append('id', id);
            formdata.append('userid', userid);
            var ajax = new XMLHttpRequest();
            ajax.upload.addEventListener('progress', progressHandler, false);
            ajax.addEventListener('load', completeHandler, false);
            ajax.addEventListener('error', errorHandler, false);
            ajax.addEventListener('abort', abortHandler, false);
            ajax.open('POST', ajaxlink);
            ajax.send(formdata);
        } else {
            window.alert('This file is in the wrong format. Allowed: AVI, MPG, MOV, WMV, MP4');
        }
    } else {
        window.alert('Choose video to upload');
    }
}