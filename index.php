<!DOCTYPE html>
<html>
<body>
    <form action="/" method="post">
        <?php
        include_once 'lib/db.php';
        include_once 'lib/table.php';
        include_once 'lib/DataSource.php';
        SqliteHelp::init();
        $ds=new DataSource(SqliteHelp::$db,"SELECT sId,sname,age,addr FROM stu",'stu');
        $tb = new table($_POST,'sId',$ds);
        echo $tb->Render();
        ?>
    </form>
</body>

</html>