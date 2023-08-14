<?php

function perpage($count, $href, $per_page = '10')
{
    $output = '';
    $paging_id = "link_perpage_box";
    if (! isset($_POST["page"])) {
        $_POST["page"] = 1;
    }
    if ($per_page != 0)
        $pages = ceil($count / $per_page);

    if ($pages >= 1) {

        if (($_POST["page"] - 3) > 0) {
            if ($_POST["page"] == 1)
                $output = $output . '<span id=1 class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary">1</span>';
            else
                $output = $output . '<input type="submit" name="page" class="btn btn-primary" value="1" />';
        }
        if (($_POST["page"] - 3) > 1) {
            $output = $output . '...';
        }

        for ($i = ($_POST["page"] - 2); $i <= ($_POST["page"] + 2); $i ++) {
            if ($i < 1)
                continue;
            if ($i > $pages)
                break;
            if ($_POST["page"] == $i)
                $output = $output . '<span id=' . $i . ' class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary" >' . $i . '</span>';
            else
                $output = $output . '<input type="submit" name="page" class="btn btn-primary" value="' . $i . '" />';
        }

        if (($pages - ($_POST["page"] + 2)) > 1) {
            $output = $output . '...';
        }
        if (($pages - ($_POST["page"] + 2)) > 0) {
            if ($_POST["page"] == $pages)
                $output = $output . '<span id=' . ($pages) . ' class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary">' . ($pages) . '</span>';
            else
                $output = $output . '<input type="submit" name="page" class="btn btn-primary" value="' . $pages . '" />';
        }
    }
    return $output;
}

function showperpage($sql, $href, $per_page = 10)
{
    require_once __DIR__ . '/list.php';
    $db_handle = new DataSource();
    $count = $db_handle->getRecordCount($sql);
    $perpage = perpage($count, $per_page, $href);
    return $perpage;
}
?>