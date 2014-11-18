'use strict';
function _(el){
    return document.getElementById(el);
}
function progressHandler(event){
    _('loaded_n_total').innerHTML = 'Uploaded '+event.loaded+' bytes of '+event.total;
    var percent = (event.loaded / event.total) * 100;
    _('progressBar').value = Math.round(percent);
    _('status').innerHTML = Math.round(percent)+'% uploaded... please wait';
}
function completeHandler(event){
    _('status').innerHTML = event.target.responseText;
    _('progressBar').value = 0;
}
function errorHandler(){
    _('status').innerHTML = 'Upload Failed';
}
function abortHandler(){
    _('status').innerHTML = 'Upload Aborted'; }
function uploadFile(ajaxlink){
    var file = _('file1').files[0];
    var form = $('#upload_form');
    var id = form.find('input[name="id"]').val();
    var userid = form.find('input[name="userid"]').val();
    var formdata = new FormData();
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
}