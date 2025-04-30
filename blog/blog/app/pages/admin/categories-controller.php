  <?php 

    //add new
    if($action == 'add')
    {
      if (user('role') != 'admin') 
      {
        echo '<div style="padding: 20px; margin-top: 40px; max-width: 600px; background: #fff0f0; color: #b30000; border: 1px solid #ffcccc; border-radius: 8px;">
          <h3 style="margin-bottom: 10px;">Access Denied</h3>
          <p>You are not authorized to perform this action.</p>
          <a href="javascript:history.back()" style="color: #007BFF;">Go Back</a>
          </div>';
          exit;
      }
        if(!empty($_POST))
        {
          
          //validate
          $errors = [];

          if(empty($_POST['category']))
          {
            $errors['category'] = "A category is required";
          }else
          if(!preg_match("/^[a-zA-Z0-9 \-\_\&]+$/", $_POST['category']))
          {
            $errors['category'] = "Category can only have letters";
          }

          $slug = str_to_url($_POST['category']);

          $query = "select id from categories where slug = :slug limit 1";
          $slug_row = query($query, ['slug'=>$slug]);
 
          if($slug_row)
          {
            $slug .= rand(1000,9999);
          }
   
          if(empty($errors))
          {
            //save to database
            $data = [];
            $data['category'] = $_POST['category'];
            $data['slug']     = $slug;
            $data['disabled'] = $_POST['disabled'];

            $query = "insert into categories (category,slug,disabled) values (:category,:slug,:disabled)";
            query($query, $data);

            redirect('admin/categories');

          }
        }
    }else
    if($action == 'edit')
    {
        
        $query = "select * from categories where id = :id limit 1";
        $row = query_row($query, ['id'=>$id]);

        if (user('role') != 'admin') 
      {
        echo '<div style="padding: 20px; margin-top: 40px; max-width: 600px; background: #fff0f0; color: #b30000; border: 1px solid #ffcccc; border-radius: 8px;">
          <h3 style="margin-bottom: 10px;">Access Denied</h3>
          <p>You are not authorized to perform this action.</p>
          <a href="javascript:history.back()" style="color: #007BFF;">Go Back</a>
          </div>';
          exit;
      }
        if(!empty($_POST))
        {

          if($row)
          {

            //validate
            $errors = [];

            if(empty($_POST['category']))
            {
              $errors['category'] = "A category is required";
            }else
            if(!preg_match("/^[a-zA-Z0-9 \-\_\&]+$/", $_POST['category']))
            {
              $errors['category'] = "Category can only have letters";
            }
     
            if(empty($errors))
            {
              //save to database
              $data = [];
              $data['category'] = $_POST['category'];
              $data['disabled'] = $_POST['disabled'];
              $data['id'] = $id;

              $query = "update categories set category = :category, disabled = :disabled where id = :id limit 1";

              query($query, $data);
              redirect('admin/categories');

            }
          }
        }
    }else
    if($action == 'delete')
    {
        
        $query = "select * from categories where id = :id limit 1";
        $row = query_row($query, ['id'=>$id]);

        if (user('role') != 'admin') 
      {
        echo '<div style="padding: 20px; margin-top: 40px; max-width: 600px; background: #fff0f0; color: #b30000; border: 1px solid #ffcccc; border-radius: 8px;">
          <h3 style="margin-bottom: 10px;">Access Denied</h3>
          <p>You are not authorized to perform this action.</p>
          <a href="javascript:history.back()" style="color: #007BFF;">Go Back</a>
          </div>';
          exit;
      }
        if($_SERVER['REQUEST_METHOD'] == "POST")
        {

          if($row)
          {

            //validate
            $errors = [];
 
            if(empty($errors))
            {
              //delete from database
              $data = [];
              $data['id']       = $id;

              $query = "delete from categories where id = :id limit 1";
              query($query, $data);
 
              redirect('admin/categories');

            }
          }
        }
      }