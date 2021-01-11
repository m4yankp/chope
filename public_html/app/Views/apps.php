<div class="container">
  <div class="row">
    <div class="col-12 col-sm8- offset-sm-2 col-md-6 offset-md-3 mt-5 pt-3 pb-3 bg-white from-wrapper">
      <div class="container">
        <h3>Create New App</h3>
        <hr>
        <?php if (session()->get('success')): ?>
          <div class="alert alert-success" role="alert">
            <?= session()->get('success') ?>
          </div>
        <?php endif; ?>
        <form class="" action="/app" method="post">
          <div class="row">
            <div class="col-12 col-sm-6">
              <div class="form-group">
               <label for="appname">App Name</label>
               <input type="text" class="form-control" name="appName" id="appname">
              </div>
            </div>
          </div>
          <?php if (isset($validation)): ?>
            <div class="col-12">
              <div class="alert alert-danger" role="alert">
                <?= $validation->listErrors() ?>
              </div>
            </div>
          <?php endif; ?>
          

          <div class="row">
            <div class="col-12 col-sm-4">
              <button type="submit" class="btn btn-primary">Create New App Credentials</button>
            </div>

          </div>
        </form>
        <hr />
        <h3>Your Apps</h3>
        <?php 
        if(count($apps)>0){
        forEach($apps as $app) { 
             ?>
             <div class="row"><div class="col-6"><strong>App Name:</strong></div><div class="col-6"><?php echo $app['appName']; ?></div></div>
             <div class="row"><div class="col-6"><strong>Client ID:</strong></div><div class="col-6"><?php echo $app['clientid']; ?></div></div>
             <div class="row"><div class="col-6"><strong>Client Key:</strong></div><div class="col-6"><?php echo $app['clientkey']; ?></div></div>
             <hr/>
             <?php
            ?> <?php }} else { ?> 
            <h4>No Apps Found</h4>
        <?php } ?>
      </div>
    </div>
  </div>
</div>