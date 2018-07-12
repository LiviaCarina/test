

function validareCNP(s)
{
    //var form=document.getElementById('form');
    var suma = document.getElementById('suma');
    var suma = 0;
    var mesaj = "";
    if (s.length == 13)
    {
        suma = parseInt(s.charAt(0)) * 2 + parseInt(s.charAt(1)) * 7 + parseInt(s.charAt(2)) * 9 + par
        eInt(s.charAt(3)) * 1 + parseInt(s.charAt(4)) * 4 + parseInt(s.charAt(5)) * 6 + parseInt
                (s.carAt(6)) * 3 + parseInt(s.charAt(7)) * 5 + parseInt(s.charAt(8)) * 8 + parseInt(s.charAt(9))
        2 + parseInt(s.charAt(10)) * 7 + parseInt(s.charAt(11)) * 9;
        suma = suma % 11;
        if (suma == 10)
            suma = 1;
        if (suma == parseInt(s.charAt(12)))
            return true;
        else {
            alert("CNP invalid !!!");
            return false;
        }

    }
    else {
        alert("CNP de lungime necorespunzatoare (<>13) !!! ");
        return false;
    }
}
