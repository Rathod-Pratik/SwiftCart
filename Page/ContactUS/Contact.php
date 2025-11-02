<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <?php include __DIR__ . '/../../Componenets/Other/Header.php'; ?>
</head>

<body>
    <?php require __DIR__ . '/../../Componenets/Home/Navbar.php' ?>
    <?php include __DIR__ . '/../../Componenets/Contact/ContactHeader.php'; ?>
    <?php include __DIR__ . '/../../Componenets/Contact/ContactForm.php'; ?>
    <?php require __DIR__ . '/../../Componenets/Home/Services.php' ?>
    <?php include __DIR__ . '/../../Componenets/Home/Footer.php'; ?>

    <script>
        document.getElementById('ContactForm').addEventListener('submit',function(e){
            e.preventDefault()
            const form=e.target;
            const formData=new FormData(form);
            formData.append('action','create')

            fetch('/SwiftCart/AJAX/Contact_ajax.php',{
                method:"POST",
                body:formData
            }).then(res => res.json()).then((res)=>{
                if(res.Success){
                    showToast("Message Send Successfully","success");
                    document.getElementById('ContactForm').reset()
                }
            })
        })
    </script>
</body>

</html>