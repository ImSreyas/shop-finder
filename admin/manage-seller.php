<?php
session_start();
if ($_SESSION['admin-id'] != 1) {
    header('location:../index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="css/se-ma.css">
    <link rel="stylesheet" href="../css/load.css">
    <script src="../jquery/jquery.js"></script>
</head>

<body class="real-body">
    <?php include('../loader/loading-div.html');?>
    <script src="../js/loading-div-slow.js"></script>
    <script>history.replaceState({},'','manage-seller.php')</script>
    <!-- menu -->
    <nav class="nav-bar">
        <ul>
            <a href="manage-seller.php" class="no-text-decoration">
                <li class="category highlight">manage seller</li>
            </a>
            <a href="manage-customer.php" class="no-text-decoration">
                <li class="order">manage customer</li>
            </a>
            <a href="system.php" class="no-text-decoration">
                <li class="profile">system control</li>
            </a>
            <a href="log-out.php" class="no-text-decoration">
                <li class="login">log out</li>
            </a>
        </ul>
    </nav>
    <!-- body -->
    <div class="btn-container-wrapper">
        <div class="search-bar-wrapper">
            <?php include('../search-bar/search-bar.html')?>
        </div>
        <div class="btn-container">
            <button class="request-btn" selected=true onclick="btnClicked(0)">Request</button>
            <button class="sellers-btn" selected=false onclick="btnClicked(1)">Sellers</button>
            <button class="removed-btn" selected=false onclick="btnClicked(2)">Removed</button>
        </div>
    </div>
    <div class="body admin-body">

    </div>
    <script>
    //-search functionalities : START

    function search(item){
        const cards = document.querySelectorAll(".seller-card-request-container")
        Array.prototype.forEach.call(cards, (card) => {
            card.setAttribute('show',false)
        })
        const cards2 = document.querySelectorAll(".seller-card-container")
        Array.prototype.forEach.call(cards2, (card) => {
            card.setAttribute('show',false)
        })
        let i, j
        const text = item.value.toLowerCase()
        const names = document.querySelectorAll(".name")
        Array.prototype.forEach.call(names, (name) => {
            let check = true
            j = 0
            nameText = name.innerHTML.toLowerCase()
            for(i=0; i<text.length; i++){
                if(nameText.includes(text[i], j)){
                    j = nameText.indexOf(text[i], j) + 1
                    continue
                }
                check = false
                break
            }
            if(check == true){
                let c = name.parentElement.parentElement.parentElement
                c.setAttribute('show',true)
            }
        })
    }
    
    let searchI = document.querySelector(".search-clear")
    searchI.addEventListener("click", () =>{
        const ch = document.querySelector(".btn-container").children
        Array.prototype.forEach.call(ch, (btn, i) => {
            if((btn.getAttribute("selected")) === 'true') {
                if(i == 0) getRequest()
                else if(i == 1) getSellers (1)
                else getSellers(2)
            }
        })
    })

    //-search functionalities : ENDS 


    function btnClicked(value){
        const ch = document.querySelector(".btn-container").children
        Array.prototype.forEach.call(ch, (btn) => {
            btn.setAttribute("selected",false)
        })
        ch[value].setAttribute("selected",true)
        if(value == 0) getRequest()
        else if(value == 1) getSellers(1)
        else getSellers(2)
    }
    getRequest()
    function getRequest(){
        $.ajax({
            url:'php/get-request.php',
            type:'POST',
            success: (data) => {
                $(".body").html(data)
            }
        })
    }
    function getSellers(value){
        $.ajax({
            url:'php/get-sellers.php',
            type:'POST',
            data: {wh: value},
            success: (data) => {
                $(".body").html(data)
            }
        })
    }
    function removeSeller(item){
        const id = item.getAttribute("id");
        $.ajax({
            url:'php/remove-seller.php',
            type:'POST',
            data: {id : id},
            success: () => {
                getSellers(1)
            }
        })
    }
    function acceptOrReject(item, value){
        const id = item.getAttribute("id")
        value = (value == 1) ? 1 : -1
        if(value == 1){
            $.ajax({
                url:'php/accept-or-reject-request.php',
                type:'POST',
                data: {id : id, value: value},
                success: () => {
                    getRequest()
                }
            })
        } else {
           $.ajax({
                url:'php/accept-or-reject-request.php',
                type:'POST',
                data: {id : id, value: value},
                success: () => {
                    getRequest()
                }
            })  
        }
    }
    function undoRemove(item){
        const id = item.getAttribute("id");
        $.ajax({
            url:'php/undo-remove-seller.php',
            type:'POST',
            data: {id : id},
            success: () => {
                getSellers(2)
            }
        })
    }
    
    </script>
</body>

</html>