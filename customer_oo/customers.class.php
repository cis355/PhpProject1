<?php

class Customers {
    
    public $id;
    
    public $name;
    public $email;
    public $mobile;
    
    private $noerrors = true;
    
    private $nameError = null;
    private $emailError = null;
    private $mobileError = null;
    
    private $title = "Customer";
    
    function create_record() { // display "create" form
        echo "
        <html>
            <head>
                <title>Create a $this->title</title>
                    ";
        echo "
                <meta charset='UTF-8'>
                <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css' rel='stylesheet'>
                <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js'></script>
                    "; 
        echo "
            </head>";
        echo "
            <body>
                <div class='container'>
                    <div class='span10 offset1'>
                        <p class='row'>
                            <h3>Create a $this->title</h3>
                        </p>
                        <form class='form-horizontal' action='customer.php?fun=11' method='post'>                        
                    ";
        $this->control_group("name", $this->nameError, $this->name);
        $this->control_group("email", $this->emailError, $this->email);
        $this->control_group("mobile", $this->mobileError, $this->mobile);
        echo " 
                            <div class='form-actions'>
                                <button type='submit' class='btn btn-success'>Create</button>
                                <a class='btn btn-secondary' href='customer.php'>Back</a>
                            </div>
                        </form>
                    </div>

                </div> <!-- /container -->
            </body>
        </html>
                    ";
    }
    
    function list_records() {
        echo "
        <html>
            <head>
                <title>$this->title" . "s" . "</title>
                    ";
        echo "
                <meta charset='UTF-8'>
                <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css' rel='stylesheet'>
                <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js'></script>
                    ";  
        echo "
            </head>
            <body>
                <div class='container'>
                    <p class='row'>
                        <h3>$this->title" . "s" . "</h3>
                    </p>
                    <p>
                        <a href='customer.php?fun=1' class='btn btn-success'>Create</a>
                    </p>
                    <div class='row'>
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                    ";
        $pdo = Database::connect();
        $sql = "SELECT * FROM customers ORDER BY id DESC";
        foreach ($pdo->query($sql) as $row) {
            echo "<tr>";
            echo "<td>". $row["name"] . "</td>";
            echo "<td>". $row["email"] . "</td>";
            echo "<td>". $row["mobile"] . "</td>";
            echo "<td width=250>";
            echo "<a class='btn btn-info' href='customer.php?fun=2&id=".$row["id"]."'>Read</a>";
            echo "&nbsp;";
            echo "<a class='btn btn-warning' href='customer.php?fun=3&id=".$row["id"]."'>Update</a>";
            echo "&nbsp;";
            echo "<a class='btn btn-danger' href='customer.php?fun=4&id=".$row["id"]."'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        Database::disconnect();        
        echo "
                            </tbody>
                        </table>
                    </div>
                </div>

            </body>

        </html>
                    ";  
    } // end list_records()
    

    function control_group ($label, $labelError, $val, $modifier="") {
        echo "<div class='control-group";
        echo !empty($labelError) ? ' alert alert-danger ' : '';
        echo "'>";
        echo "<label class='control-label'>$label</label>";
        echo "<div class='controls'>";
        echo "<input "
            . "name='$label' "
            . "type='text' "
            . "$modifier "
            . "placeholder='$label' "
            . "value='";
        echo !empty($val) ? $val : '';
        echo "'>";
        if (!empty($labelError)) {
            echo "<span class='help-inline'>";
            echo "&nbsp;&nbsp;" . $labelError;
            echo "</span>";
        }
        echo "</div>";
        echo "</div>";
    }
    
    function insert_db_record () {
        
        // validate input
        $valid = true;
        if (empty($this->name)) {
            $this->nameError = 'Please enter Name';
            $valid = false;
        }

        if (empty($this->email)) {
            $this->emailError = 'Please enter Email Address';
            $valid = false;
        } 
        else if ( !filter_var($this->email,FILTER_VALIDATE_EMAIL) ) {
            $this->emailError = 'Please enter a valid email address: me@mydomain.com';
            $valid = false;
        }

        if (empty($this->mobile)) {
            $this->mobileError = 'Please enter Mobile Number';
            $valid = false;
        }

        // insert data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO customers (name,email,mobile) values(?, ?, ?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($this->name,$this->email,$this->mobile));
            Database::disconnect();
            header("Location: customer.php");
        }
        else {
            $this->create_record(); // go back to "create" form
        }
    }
    function select_db_record($id) {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM customers where id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->mobile = $data['mobile'];
    }
    
    function read_record($id) { // display "read" form
        $this->select_db_record($id);
        echo "
        <html>
            <head>
                <title>Read a $this->title</title>
                    ";
        echo "
                <meta charset='UTF-8'>
                <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css' rel='stylesheet'>
                <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js'></script>
                    "; 
        echo "
            </head>";
        echo "
            <body>
                <div class='container'>
                    <div class='span10 offset1'>
                        <p class='row'>
                            <h3>Read a $this->title</h3>
                        </p>
                        <form class='form-horizontal' action='customer.php?fun=0' method='post'>                        
                    ";
        $this->control_group("name", $this->nameError, $this->name, "readonly");
        $this->control_group("email", $this->emailError, $this->email, "readonly");
        $this->control_group("mobile", $this->mobileError, $this->mobile, "readonly");
        echo " 
                            <div class='form-actions'>

                                <a class='btn btn-secondary' href='customer.php'>Back</a>
                            </div>
                        </form>
                    </div>

                </div> <!-- /container -->
            </body>
        </html>
                    ";
    }
    
    function delete_db_record($id) {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM customers WHERE id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        Database::disconnect();
        header("Location: customer.php");
    }
    
    function delete_record($id) { // display "read" form
        $this->select_db_record($id);
        echo "
        <html>
            <head>
                <title>Delete a $this->title</title>
                    ";
        echo "
                <meta charset='UTF-8'>
                <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css' rel='stylesheet'>
                <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js'></script>
                    "; 
        echo "
            </head>";
        echo "
            <body>
                <div class='container'>
                    <div class='span10 offset1'>
                        <p class='row'>
                            <h3>Delete a $this->title</h3>
                        </p>
                        <form class='form-horizontal' action='customer.php?fun=44&id=$id' method='post'>                        
                    ";
        $this->control_group("name", $this->nameError, $this->name, "readonly");
        $this->control_group("email", $this->emailError, $this->email, "readonly");
        $this->control_group("mobile", $this->mobileError, $this->mobile, "readonly");
        echo " 
                            <div class='form-actions'>
                                <button type='submit' class='btn btn-danger'>Delete</button>
                                <a class='btn btn-secondary' href='customer.php'>Back</a>
                            </div>
                        </form>
                    </div>

                </div> <!-- /container -->
            </body>
        </html>
                    ";
    }
    
    function update_record($id) { // display "update" form
        if($this->noerrors) $this->select_db_record($id);
        echo "
        <html>
            <head>
                <title>Update a $this->title</title>
                    ";
        echo "
                <meta charset='UTF-8'>
                <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css' rel='stylesheet'>
                <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js'></script>
                    "; 
        echo "
            </head>";
        echo "
            <body>
                <div class='container'>
                    <div class='span10 offset1'>
                        <p class='row'>
                            <h3>Update a $this->title</h3>
                        </p>
                        <form class='form-horizontal' action='customer.php?fun=33&id=$id' method='post'>                        
                    ";
        $this->control_group("name", $this->nameError, $this->name);
        $this->control_group("email", $this->emailError, $this->email);
        $this->control_group("mobile", $this->mobileError, $this->mobile);
        echo " 
                            <div class='form-actions'>
                                <button type='submit' class='btn btn-warning'>Update</button>
                                <a class='btn btn-secondary' href='customer.php'>Back</a>
                            </div>
                        </form>
                    </div>

                </div> <!-- /container -->
            </body>
        </html>
                    ";
    }
    
    function update_db_record ($id) {
        $this->id = $id;
        // validate input
        $valid = true;
        if (empty($this->name)) {
            $this->nameError = 'Please enter Name';
            $valid = false;
        }

        if (empty($this->email)) {
            $this->emailError = 'Please enter Email Address';
            $valid = false;
        } 
        else if ( !filter_var($this->email,FILTER_VALIDATE_EMAIL) ) {
            $this->emailError = 'Please enter a valid email address: me@mydomain.com';
            $valid = false;
        }

        if (empty($this->mobile)) {
            $this->mobileError = 'Please enter Mobile Number';
            $valid = false;
        }

        // update data
        if ($valid) {
            $this->noerrors = true;
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE customers  set name = ?, email = ?, mobile = ? WHERE id = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($this->name,$this->email,$this->mobile,$this->id));
            Database::disconnect();
            header("Location: customer.php");
        }
        else {
            $this->noerrors = false;
            $this->update_record($id);  // go back to "update" form
        }
    }
} // end class Customers