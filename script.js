var script = document.createElement('script');
script.src = 'https://code.jquery.com/jquery-2.1.4.min.js';
script.type = 'text/javascript';
document.getElementsByTagName('head')[0].appendChild(script);

var products = []
var cart = []
var total = 0
var session = ""

function get_products(cat) {
    ind = [];
    for (i=0; i<products.length; i++) {
        if (products[i].category == cat) {
            ind = ind.concat(i);
        }
    }
    return ind;
}

function make_session(sess) {
    session = sess;
}

function spawn_buttons(category) {
    document.getElementById("product_group").innerHTML = "<h1>"+category+"</h1>";

    var ind = get_products(category);

    for (i = 0; i < ind.length; i++) {
        document.getElementById("btn" + (i + 1)).innerHTML = '<a href="#" onclick=\'add_cart("'+products[ind[i]].product+'")\' class="btn btn-default" style="background-image: url('+products[ind[i]].image+')"></a><br>'+products[ind[i]].product+' €'+products[ind[i]].price;
    }
    for (i = ind.length; i < 12; i++) {
        document.getElementById("btn" + (i + 1)).innerHTML = '';
    }
}

function emptycart() {
    cart = [];
    total = 0;
    document.getElementById("shoppingcart").innerHTML = cart;
    empty_cart.style.display = 'none';
    view_total.style.display = 'none';
}

function add_cart(product) {
    for (i = 0; i < cart.length; i++) {
        if (cart[i][0] == product) {
            cart[i][1] += 1;
            break
        } else if (i == cart.length - 1) {
            if (cart.length <= 10) {
                cart[i + 1] = [product, 1];
                break
            }
        }
    }

    if (product == "Beer") {
        document.getElementById("myAudio").play();
    }

    if (cart.length == 0) {
    cart = [[product, 1]];
    }

    cart_str = "";

    for (i=0; i < products.length; i++) {
        if (products[i].product == product) {
            total = (+total + +products[i].price).toFixed(2);
        }
    }

    for(i = 0; i < cart.length; i++) {
        cart_str = cart_str.concat(cart[i][1] + " x " + cart[i][0] + "<br>");
    }

    document.getElementById("view_total").innerHTML = "<br><b>Total: €" + total + "</b><br>";
    document.getElementById("shoppingcart").innerHTML = cart_str;
    empty_cart.style.display = 'block';
    view_total.style.display = 'block';
}

function add_product(product) {
    products = products.concat([product]);
    //document.getElementById("shoppingcart").innerHTML = products;
}

function log(action, success, info) {
    $.ajax({
        url: "log.php",
        type: "POST",
        data: {action : action, success : success, info : info},
        success: function(data) {
        },
        error: function(data) {
            console.log(data)
        }
    });
}

function show_denial_image() {
    document.getElementById("denial").style.display = "block";
    setTimeout(function() {document.getElementById("denial").style.display = "none"}, 2000)
    log("checkout", 0, "checkout fail" + SN);
}

function checkout(user) {
    if (cart.length != 0) {
        if (user.length == 7) {
            items = [];
            amounts = [];

            for (i=0; i<cart.length; i++) {
                items = items.concat(cart[i][0]);
                amounts = amounts.concat(cart[i][1]);
            }

            $.ajax({
                url: "checkout.php",
                type: "POST",
                data: {user : user, items : items, amounts : amounts, session : session},
                success: function(data) {
                    alert(data);
                    if (data.length > 0) {
                        if (data == "Purchase successful") {
                            emptycart();
                            if (user == "2004933") {
                                document.getElementById("myPopup2").style.display = "none";
                                document.getElementById("confirmation2").style.display = "block";
                                setTimeout(function() {document.getElementById("confirmation2").style.display = "none"}, 2000)
                            } else if (user == "1831828") {
                                document.getElementById("myPopup2").style.display = "none";
                                document.getElementById("confirmation3").style.display = "block";
                                setTimeout(function() {document.getElementById("confirmation3").style.display = "none"}, 2000)
                            } else {
                                document.getElementById("myPopup2").style.display = "none";
                                document.getElementById("confirmation").style.display = "block";
                                setTimeout(function() {document.getElementById("confirmation").style.display = "none"}, 2000)
                            }
                        } else if (data == "Student number checkoud disabled") {
                          log("checkout", 0, "student number checkout disabled");
                          show_denial_image();
                          alert("Student number checkout is disabled! This can be changed via the website.");
                        } else {
                            show_denial_image();
                        }
                    } else {
                        show_denial_image();
                    }
                },
                error: function(data) {
                    console.log(data)
                    show_denial_image();
                }
            });
        }
    } else {
        show_denial_image();
    }
}

function random() {
    possibilities = []
    for (i=0; i < products.length; i++) {
        if (products[i].random == true) {
            possibilities.push(products[i].product);
        }
    }
    add_cart(possibilities[Math.floor(Math.random()*possibilities.length)]);
}

//Card reader
socket = new WebSocket("ws://localhost:3000", "nfc");
socket.onmessage = function(msgevent) {
    var msg = JSON.parse(msgevent.data);
    id = msg.uid;
    $.ajax({
        url: "read_card.php",
        type: "POST",
        data: {uid : id},
        success: function(data) {
            if (data == 'Card not registered!') {
                alert(data);
                SN = prompt("Student number:");
                if (SN.length == 7) {
                    $.ajax({
                        url: "register_card.php",
                        type: "POST",
                        data: {SN : SN, uid : id},
                        succes: function(data) {
                            alert(data)
                        },
                        error: function(data) {
                            alert("Card failed to register!")
                        }
                    });
                } else {
                    alert("Student number not valid!")
                }
            } else if (data == 'Card checkout is disabled!') {
              log("card_checkout", 0, "card checkout disabled");
              show_denial_image();
              alert("Card checkout is disabled! This can be changed via the website.");
            } else {
                user = data
                if (cart.length > 0) {
                    if (user.length == 7) {
                        items = [];
                        amounts = [];
                        for (i=0; i<cart.length; i++) {
                            items = items.concat(cart[i][0]);
                            amounts = amounts.concat(cart[i][1]);
                        }

                        $.ajax({
                            url: "checkout.php",
                            type: "POST",
                            data: {user : user, items : items, amounts : amounts, session : session},
                            success: function(data) {
                                if (data.length > 0) {
                                    emptycart();
                                    if (data == "Purchase succesful") {
                                        if (user == "2004933") {
                                            document.getElementById("myPopup2").style.display = "none";
                                            document.getElementById("confirmation2").style.display = "block";
                                            setTimeout(function() {document.getElementById("confirmation2").style.display = "none"}, 2000);
                                        } else if (user == "1831828") {
                                            document.getElementById("myPopup2").style.display = "none";
                                            document.getElementById("confirmation3").style.display = "block";
                                            setTimeout(function() {document.getElementById("confirmation3").style.display = "none"}, 2000)
                                        } else {
                                            document.getElementById("myPopup2").style.display = "none";
                                            document.getElementById("confirmation").style.display = "block";
                                            setTimeout(function() {document.getElementById("confirmation").style.display = "none"}, 2000);
                                        }
                                    } else {
                                        show_denial_image();
                                    }
                                } else {
                                    show_denial_image();
                                }
                            },
                            error: function(data) {
                                console.log(data)
                            }
                        });
                    }
                } else {
                    show_denial_image();
                }

                return;
            }
        },
        error: function(data) {

        }
    });
};

function scanproduct(barcode){
    $.ajax({
        url: "https://tally.sa-atlantis.nl/barcode.php",
        type: "POST",
        data: {barcode : barcode},
        success: function(data) {
            if (data != 'Barcode not found!!') {
                add_cart(data);
            }
        },
        error: function(data) {
            console.log(data);
            alert("FOUT!");
        }
    });
};
