<form action="EnquiryResponse" method="POST">    <?php foreach($enquirie as $enquiry) { ?>        <input type="hidden" name="Staffid" value ="<?= $_SESSION['userId'];?>" /><!--    <label for = "surname">Surname</label>--><!--    <input type ="text"  name="surname" value=""/>-->    <?php echo $enquiry['id']; ?>    <textarea name ="EnquiryResponse">    </textarea>        <input type="submit" name="submit" value="Add" />    <?php } ?></form>