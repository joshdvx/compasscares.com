<?php 

/**
 * Apply for a job form
 * 
 * Displays form that allows to apply for a selected job
 * 
 * @author Greg Winiarski
 * @package Templates
 * @subpackage JobBoard
 */

 /* @var $members_only bool True if only registered members can apply for jobs */
 /* @var $application_sent bool True if form was just submitted */
 /* @var $job Wpjb_Model_Job */
 /* @var $form Wpjb_Form_Apply */

?>


<div id="wpjb-main" class="wpjb-page-apply">
<h3>You are applying for <span><?php esc_html_e($job->job_title) ?></span></h3>
    <?php wpjb_flash(); ?>
    
    <?php if(isset($members_only) && $members_only): ?>
        <a href="<?php echo wpjb_link_to("job", $job) ?>"><?php _e("Go back to job details.", WPJB_DOMAIN) ?></a>
    <?php else: ?>

    <?php if(!isset($application_sent)): ?>
    <form id="wpjb-apply-form" action="" method="post" enctype="multipart/form-data" class="wpjb-form">
        <?php wpjb_form_render_hidden($form) ?>
        <?php foreach($form->getNonEmptyGroups() as $group): ?>
        <?php /* @var $group stdClass */ ?> 
        <fieldset class="wpjb-fieldset-<?php echo $group->name ?>">
            <legend class="wpjb-empty"><?php esc_html_e($group->legend) ?></legend>
            <?php foreach($group->element as $name => $field): ?>
            <?php /* @var $field Daq_Form_Element */ ?>
            <div class="<?php wpjb_form_input_features($field) ?>">

                <label class="wpjb-label">
                    <?php esc_html_e($field->getLabel()) ?>
                    <?php if($field->isRequired()): ?><span class="wpjb-required">*</span><?php endif; ?>
                </label>
                
                <div class="wpjb-field">
                    <?php wpjb_form_render_input($form, $field) ?>
                    <?php wpjb_form_input_hint($field) ?>
                    <?php wpjb_form_input_errors($field) ?>
                </div>

            </div>
            <?php endforeach; ?>
        </fieldset>
        <?php endforeach; ?>
        
        <h3>Drug and/or alcohol pre-employment screening procedures</h3>
        <p>C.O.M.P.A.S.S., LLC has adopted pre-employment screening practices for all candidates for employment. These practices are designed to avoid the hiring of individuals whose use of drugs or alcohol indicates a potential for impaired or unsafe job performance. All candidates/participants who have received an offer of employment with C.O.M.P.A.S.S., LLC will be tested. Employment will be contingent upon passing drug and/or alcohol screen.</p>
        
        <h3>Authorization</h3>
        <p>By clicking the "Send Application" button, I certify that the facts contained in this application are true and complete to the best of my knowledge and understand that, if employed, falsified statements on this application shall be grounds for immediate termination. I authorize investigation of all statements contained herein and the references, schools, and employers listed above to give you any and all information concerning my previous employment and any pertinent information they may have, personal or otherwise, and release C.O.M.P.A.S.S.., LLC for any damage that may result from utilization of such information. Furthermore, I understand that just as I am free to resign at any time, C.O.M.P.A.S.S., LLC reserves the right to terminate my employment at any time, with or without cause and without prior notice. I understand that no representative of C.O.M.P.A.S.S., LLC has the authority to make any assurances to the contrary.</p>

        <fieldset>
            <div>
                <legend class="wpjb-empty"></legend>
                <input type="submit" name="wpjb_preview" id="wpjb_submit" value="<?php _e("Send Application", WPJB_DOMAIN) ?>" />
                <?php _e("or", WPJB_DOMAIN); ?>
                <a href="<?php echo wpjb_link_to("job", $job) ?>"><?php _e("cancel and go back", WPJB_DOMAIN) ?></a>
            </div>
        </fieldset>

    </form>
    <?php else: ?>
    <a class="wpjb-button wpjb-cancel" href="<?php echo wpjb_link_to("job", $job) ?>"><?php _e("&larr; Go back to job details.", WPJB_DOMAIN) ?></a>
    <?php endif; ?>

    <?php endif; ?>
</div>
