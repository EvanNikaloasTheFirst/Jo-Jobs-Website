<h2>Contact us</h2><main>    <form action="contact" method="POST" ><!--        <input type="hidden" name="id" value=""/>-->        <label>Name</label>        <input type="text" name="name" />        <label>Email</label>        <input type="text" name="email" />        <label>Telephone</label>        <input type="text" name="telephone" />        <label>Enquiry</label>        <textarea name = "enquiry">        </textarea>        <input type="hidden" name="userId" value = "<?php if (isset($_SESSION['userId'])){            echo $_SESSION['userId'];        }else {            echo "";        }  ?> "/>        <input type="submit" name="submit" value="Add" />    </form></main>