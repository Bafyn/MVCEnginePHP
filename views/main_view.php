<h1>Welcome!</h1>
<table style="border: 1px solid grey;font: 14px sans-serif; background: lightyellow">
    All news
    <tr>
        <td>Title</td>
        <td>Content</td>
        <td>Publication date</td>
    </tr>
    <?php
    foreach ($data as $row) {
        echo '<tr><td>' . $row['title'] . '</td><td>' . $row['content'] . '</td><td>' . $row['pub_date'] . '</td><tr>';
    }
    ?>
</table>