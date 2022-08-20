<?php 

require_once plugin_dir_path( dirname( __FILE__ ) ) . "class-post-by-image-admin.php";

if(isset($_POST['add-post-by-image']) && $_POST['add-post-by-image'] == 1 ){
    $errors = [];
    if(isset($_POST['folder']) && $_POST['folder'] === "0"){
        $errors[] = 'Please select folder location';
    }
    if(isset($_POST['status']) && $_POST['status'] === "0"){
        $errors[] = 'Please select post status';
    }

    if(count($errors) == 0){
        $folder = $_POST['folder'];
        $status = $_POST['status'];
        $folderList = explode(',',$_POST['folderList']);
        $createPost = new Post_By_Image_Admin('post-by-image','1.0.0');
        
        $files = $createPost->dirToArray($folder);
        $sortFiles = asort($files);
        
        $rootFolderName = ucFirst(end(explode('/',ucFirst(end(explode('\\',$folder))))));
        $result = $createPost->getFilesWithCategory($files,$folderList,$status);
        $message = "Post created successfully.";
    }
}

?>
<div class="wrap">
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

    <?php 
        if(isset($errors) && count($errors) > 0){
            foreach($errors as $error){
                ?>
                <div id="setting-error-settings_updated" class="notice notice-error settings-error is-dismissible"> 
                    <p><strong><?php echo $error; ?>.</strong></p>
                    <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>
            <?php 
            }
        }
        if(isset($message) && $message){
                ?>
                <div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible"> 
                    <p><strong><?php echo $message; ?>.</strong></p>
                    <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>
            <?php
        }
        $message = '';
        $errors = [];
    ?>
    <form action="#" method="post">
        <input type="hidden" name="add-post-by-image" value="1">
        <input type="hidden" name="folderList" value="<?php echo implode(',', $folderList); ?>">
        <p>Please select the folder path and post status</p>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="pbi_settingfolder_path">Select Folder Path</label>
                    </th>
                    <td>			
                        <fieldset>
                            <label>
                                <select name="folder">
                                    <option value="0">Select Status</option>
                                    <?php foreach($folders as $key =>$value) { ?>
                                        <option value="<?php echo $value; ?>"><?php echo $key; ?></option>
                                    <?php } ?>
                                </select>
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="pbi_settingimage_type">Select Post Status</label>
                    </th>
                    <td>			
                        <fieldset>
                            <label>
                                <select name="status">
                                    <option value="0">Select Status</option>
                                    <option value="publish">Publish</option>
                                    <option value="draft">Draft</option>
                                    <option value="private">Private</option>
                                </select>
                            </label>
                        </fieldset>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Add Post"></p>    
    </form>
</div> 