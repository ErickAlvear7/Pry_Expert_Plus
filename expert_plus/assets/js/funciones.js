//FUNCIONES ALERTIFY

function mensajesalertify(_mensaje, _tipo, _position, _tiempo){
    alertify.set('notifier','position', _position);
    switch(_tipo){
       case "S": //SUCCESS
            alertify.success(_mensaje , _tiempo , function(){console.log('dismissed');});
           break; 
       case  "W":
            alertify.warning(_mensaje , _tiempo , function(){console.log('dismissed');});
           break;
       case "E":
            alertify.error(_mensaje , _tiempo , function(){console.log('dismissed');});
           break; 
        case "N":
            alertify.notify(_mensaje , _tiempo , function(){console.log('dismissed');});
            break;  
        case "M":
            alertify.message(_mensaje , _tiempo , function(){console.log('dismissed');});
            break;       
    }
}

//FUNCIONES ALERTIFY

function mensajesweetalert(_position, _icon, _title, _showconfirbutton, _timer){
    Swal.fire({
        position: _position,
        icon: _icon,
        title: _title,
        showConfirmButton: _showconfirbutton,
        timer: _timer
      })
}

function mensajetoastr(_position, _tipo, _title, _mensaje, _timer, _closebutton, _progress){
    
    toastr[_tipo](_mensaje, _title);

    toastr.options = {
        "closeButton": _closebutton,
        "debug": false,
        "newestOnTop": false,
        "progressBar": _progress,
        "positionClass": _position,
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "500",
        "timeOut": _timer,
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    

}
