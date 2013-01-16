<div id="wpjb-main" class="wpjb-page-job-application">

    <?php wpjb_flash(); ?>
    
    <div class="wpjb-menu-bar">
        <a href="<?php echo wpjb_link_to("employer_panel") ?>"><?php _e("Company jobs", WPJB_DOMAIN) ?></a>
        &raquo; 
        <a href="<?php echo wpjb_link_to("job_applications", $job) ?>"><?php esc_html_e($job->job_title) ?></a>
        &raquo;
        <?php _e($application->applicant_name) ?>
    </div>
    
    
    <table class="wpjb-info">
    <h3><?php echo $application->applicant_name ?></h3>
        <tbody>
            <tr>
                <td><?php _e("Applicant Name", WPJB_DOMAIN) ?></td>
                <td><?php echo $application->applicant_name ?></td>
            </tr>
            <tr>
                <td><?php _e("Applicant E-mail", WPJB_DOMAIN) ?></td>
                <td><?php echo $application->email ?></td>
            </tr>
            <tr>
                <td><?php _e("Date Sent", WPJB_DOMAIN) ?></td>
                <td><?php echo wpjb_date("d M, Y", $application->applied_at) ?></td>
            </tr>
            <?php foreach($application->getNonEmptyFields() as $field): ?>
            <tr>
                <td><?php echo esc_html($field->getField()->label) ?></td>
                <td><?php echo esc_html($field->value) ?></td>
                
                
            </tr>
            <?php endforeach; ?>
            
            <?php if(count($application->getFiles())): ?>
            <tr>
                <td><?php _e("Attached Files", WPJB_DOMAIN) ?></td>
                <td>
                    <?php foreach($application->getFiles() as $file): ?>
                    <a href="<?php echo esc_attr($file->url) ?>"><?php echo esc_html($file->basename) ?></a>
                    ~ <?php echo esc_html(wpjb_format_bytes($file->size)) ?>
                    <br/>
                    <?php endforeach; ?>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>