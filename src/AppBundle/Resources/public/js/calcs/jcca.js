
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


function DoIt (a,m,s,mt,g)
{
    if (mt==0)
    {
        if (g==0) return  (((140-1.0*convert(a)) *convert(m))/(72*convert(s)));
        else return ( ( ((140-1.0*convert(a))*convert(m))/(72*convert(s)))*0.85);
    }
    else
    {
        if (g==0) return (((98-0.8*(convert(a)-20))/convert(s)));
        else return ( ( ((98-0.8*(convert(a)-20))/convert(s))*0.9));
    }
}

function ChangeU(u)
{
    var i;
    var rl=document.getElementById("resun");
    for (i=rl.childNodes.length-1;i>=0;i--)
    {
        rl.removeChild(rl.childNodes[i]);
    }

    if (u==0)
    {
        var tn=document.createTextNode("мл/мин");
        rl.appendChild(tn);
    }

    if (u==1)
    {
        var tn=document.createTextNode("мл/мин/1.73 кв. м");
        rl.appendChild(tn);
    }

}

function Clear(Text)
{
    Text.value="";
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

function CheckSc(s)
{
    if (s.value=="") { s.style.borderColor='#CC0000'; alert("Уровень креатинина плазмы не задан!"); return false;}
    if (!IsPositiveNumber(s.value)) { s.style.borderColor='#CC0000'; alert("Уровень креатинина плазмы: неверный ввод!"); return false;}
    if (s.value<0.3||s.value>10) {s.style.borderColor='#CC0000'; alert ("Креатинин плазмы должен быть в пределах от 0.3 до 10 мг/дл!"); return false;}
    s.style.borderColor='#CCCCCC';
    return true;
}

function CheckMass(ht)
{
    if (ht.value=="") { ht.style.borderColor='#CC0000'; alert("Вес не задан!"); return false;}
    if (!IsPositiveNumber(ht.value)) { ht.style.borderColor='#CC0000'; alert("Вес: неверный ввод!"); return false;}
    if (ht.value<22||ht.value>200) { ht.style.borderColor='#CC0000';alert ("Вес должен быть в пределах от 22 до 200 кг!"); return false;}
    ht.style.borderColor='#CCCCCC';
    return true;
}

function CheckAge(ht)
{
    if (ht.value=="") {ht.style.borderColor='#CC0000'; alert("Возраст не задан!");  return false;}
    if (!IsPositiveNumber(ht.value)) { ht.style.borderColor='#CC0000'; alert("Возраст: неверный ввод!"); return false;}
    if (ht.value<18||ht.value>110) { ht.style.borderColor='#CC0000'; alert ("Возраст должен быть в пределах от 18 до 110 лет!"); return false;}
    ht.style.borderColor='#CCCCCC';
    return true;
}


function rnd(numb)
{
    return (Math.round(numb*100.0)/100.0);
}


