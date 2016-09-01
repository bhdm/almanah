
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

function DoIt(gen,age,ad,smoke,chol)
{
    var s0,s010,alpha,p,cs0,cs10,ncs0,ncs10,wc,wnc;
    var bchol,bsbp,bsm,cs,cs1,ncs,ncs1,r,r1;
    age=convert(age);
    ad=convert(ad);
    chol=convert(chol);
    if (gen==0)
    {
        alpha=-21.0;p=4.62;
    }
    else
    {
        alpha=-28.7;p=6.23;
    }
    cs0=Math.exp(-Math.exp(alpha)*Math.pow(age-20.0,p));
    cs10=Math.exp(-Math.exp(alpha)*Math.pow(age-10.0,p));

    if (gen==0)
    {
        alpha=-25.7;p=5.47;
    }
    else
    {
        alpha=-30.0;p=6.42;
    }


    ncs0=Math.exp(-Math.exp(alpha)*Math.pow(age-20.0,p));

    ncs10=Math.exp(-Math.exp(alpha)*Math.pow(age-10.0,p));

    bchol=0.24;
    bsbp=0.018;
    bsm=smoke*0.71;
    wc=bchol*(chol-6.0)+bsbp*(ad-120.0)+bsm;


    bchol=0.02;
    bsbp=0.022;
    bsm=smoke*0.63;
    wnc=bchol*(chol-6.0)+bsbp*(ad-120.0)+bsm;


    cs=Math.pow(cs0,Math.exp(wc));

    cs1=Math.pow(cs10,Math.exp(wc));

    ncs=Math.pow(ncs0,Math.exp(wnc));

    ncs1=Math.pow(ncs10,Math.exp(wnc));

    cs1=cs1/cs;
    ncs1=ncs1/ncs;

    r=1.0-cs1;
    r1=1.0-ncs1;
    return(100.0*(r+r1));
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

function CheckChol(cmes)
{
    if (cmes.value=="") {alert("Уровень холестерина: неверный ввод!"); return false;}
    if (!IsPositiveNumber(cmes.value)) {alert("Уровень холестерина должен быть задан положительным числом!"); return false;}
    if (cmes.value<3||cmes.value>8) {alert ("Уровень холестерина должен быть в пределах от 3 до 8 ммоль/л!"); return false;}

    return true;
}


function CheckAD(ames)
{
    if (ames.value=="") {alert("Уровень систолического АД не задан!"); return false;}

    if (!IsPositiveNumber(ames.value)) {alert("Уровень систолического АД: неверный ввод!"); return false;}

    if (ames.value<100||ames.value>180) {alert ("Уровень систолического АД должен быть в пределах от 100 до 180 мм рт.ст.!"); return false;}

    return true;
}

function CheckAge(ames)
{
    if (ames.value=="") {alert("Возраст не задан!"); return false;}

    if (!IsPositiveNumber(ames.value)) {alert("Возраст: неверный ввод!"); return false;}

    if (ames.value<10||ames.value>65) {alert ("Возраст должен быть в пределах от 10 до 65 лет!"); return false;}
    return true;
}


function rnd(numb)
{
    return (Math.round(numb*100.0)/100.0);
}

