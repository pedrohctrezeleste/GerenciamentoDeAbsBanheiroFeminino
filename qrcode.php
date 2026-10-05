<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gerador de QR Code</title>

    <!-- QR Code -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

</head>

<body>

<h1>Gerador de QR Code</h1>

<p>Escolha o piso para gerar o QR Code.</p>

<hr>

<label>Escolha o piso:</label>

<br><br>

<select id="piso">

    <option value="1">Piso 1</option>
    <option value="2">Piso 2</option>
    <option value="3">Piso 3</option>
    <option value="4">Piso 4</option>

</select>

<br><br>

<button onclick="gerarQRCode()">
    Gerar QR Code
</button>

<button onclick="gerarPDF()">
    Exportar QR Code em PDF
</button>

<br><br>

<div id="qrcode"></div>

<br>

<p id="link"></p>


<script>

function gerarQRCode() {

    let piso = document.getElementById("piso").value;

    let endereco =
        "https://pracantar.com/abcontrol/piso"
        + piso
        + ".php";

    document.getElementById("qrcode").innerHTML = "";

    new QRCode(document.getElementById("qrcode"), {

        text: endereco,

        width: 250,

        height: 250

    });

    document.getElementById("link").innerHTML =
        "<strong>Link:</strong><br>" + endereco;

}


/*
========================================
GERAR PDF
========================================
*/

function gerarPDF() {

    const { jsPDF } = window.jspdf;

    let piso = document.getElementById("piso").value;

    let endereco =
        "https://pracantar.com/abcontrol/piso"
        + piso
        + ".php";


    // Cria um QR Code temporário
    let divQR = document.createElement("div");

    divQR.style.position = "absolute";

    divQR.style.left = "-9999px";

    document.body.appendChild(divQR);


    new QRCode(divQR, {

        text: endereco,

        width: 500,

        height: 500

    });


    // Aguarda o QR Code ser criado
    setTimeout(function() {

        let canvas = divQR.querySelector("canvas");

        if (!canvas) {

            alert("Erro ao gerar o QR Code.");

            return;

        }


        let imagemQR = canvas.toDataURL("image/png");


        // Criar PDF A4
        let pdf = new jsPDF({

            orientation: "portrait",

            unit: "mm",

            format: "a4"

        });


        // Título
        pdf.setFontSize(24);

        pdf.text(
            "ABSORVENTES GRATUITOS",
            105,
            35,
            { align: "center" }
        );


        // Piso
        pdf.setFontSize(30);

        pdf.text(
            "BANHEIRO FEMININO",
            105,
            55,
            { align: "center" }
        );


        pdf.setFontSize(32);

        pdf.text(
            "PISO " + piso,
            105,
            75,
            { align: "center" }
        );


        // QR Code
        pdf.addImage(

            imagemQR,

            "PNG",

            45,

            90,

            120,

            120

        );


        // Instrução
        pdf.setFontSize(18);

        pdf.text(
            "APONTE A CÂMERA DO CELULAR",
            105,
            225,
            { align: "center" }
        );


        pdf.text(
            "PARA O QR CODE",
            105,
            235,
            { align: "center" }
        );


        // Link
        pdf.setFontSize(10);

        pdf.text(
            endereco,
            105,
            255,
            { align: "center" }
        );


        // Salvar
        pdf.save("QR_Code_Piso_" + piso + ".pdf");


        // Remove QR temporário
        document.body.removeChild(divQR);


    }, 500);

}

</script>

</body>

</html>