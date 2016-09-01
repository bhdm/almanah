
function convert(txt)
{
    var i, tt="";
    for (i=0;i<txt.length;i++)
    {
        if (txt.charAt(i)==",") tt=tt+".";
        else tt=tt+txt.charAt(i);
    }
    return tt;
}

function ShowRes(res)
{
    var txt;
    var rl=document.getElementById("resline");
    if (res<18.5) {txt="ПОНИЖЕННЫЙ ВЕС";$(rl).css("color","#f79008");}
    if (res>=18.5&&res<=25)  {txt="НОРМА";$(rl).css("color","#008000");}
    if (res>25&&res<=29.9) {txt="ИЗБЫТОЧНЫЙ ВЕС";$(rl).css("color","#f79008");}
    if (res>30) {txt="ОЖИРЕНИЕ";$(rl).css("color","#FF0000");}
// if (res>35&&res<=39.9) {txt="ОЖИРЕНИЕ II СТЕПЕНИ";rl.setAttribute("color","#FF0000");}
// if (res>40) {txt="ОЖИРЕНИЕ III СТЕПЕНИ";rl.setAttribute("color","#FF0000");}

    for (i=rl.childNodes.length-1;i>=0;i--)
    {
        rl.removeChild(rl.childNodes[i]);
    }
    var tn=document.createTextNode(txt);
    rl.appendChild(tn);
    return true;
}

function DoIt (h,m)
{
    var  res=(10000*convert(m)/(convert(h)*convert(h)));
    ShowRes(res);
    return res;
}


function Clear(Text)
{
    Text.value="";
}

function ClrStr()
{
    var rl=document.getElementById("resline");
    for (i=rl.childNodes.length-1;i>=0;i--)
    {
        rl.removeChild(rl.childNodes[i]);
    }
}

function mine()
{
    alert("ok");
}


function IsPositiveNumber(txt)
{
    var j=0;
    var aa;
    for (i=0;i<txt.length;i++)
    {
        aa=txt.charAt(i);
        if (aa<"0"||aa>"9")
            if (aa!="."&&aa!=",") return false;
            else if (j==1) return false;
            else j++;
    }
    return true;
}

function CheckMass(mass)
{
    if (mass.value=="") {alert("Масса тела не задана!"); return false;}
    if (!IsPositiveNumber(mass.value)) {alert("Масса тела: неверный ввод!"); return false;}
    if (mass.value<1||mass.value>200) {alert ("Масса тела должна быть в пределах от 1 до 200 кг!"); return false;}

    return true;
}

function CheckHt(ht)
{
    if (ht.value=="") {alert("Рост не задан!"); return false;}
    if (!IsPositiveNumber(ht.value)) {alert("Рост: неверный ввод!"); return false;}
    if (ht.value<24||ht.value>229) {alert ("Рост должен быть в пределах от 24 до 229 см!"); return false;}

    return true;
}



function rnd(numb)
{
    return (Math.round(numb*100.0)/100.0);
}

