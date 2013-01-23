<?php

/**
 * Jobs list
 * 
 * This template file is responsible for displaying list of jobs on job board
 * home page, category page, job types page and search results page.
 * 
 * 
 * @author Greg Winiarski
 * @package Templates
 * @subpackage JobBoard
 * 
 * @var $jobList array List of jobs to display
 * @var $current_category Wpjb_Model_Category Available if browsing jobs by category
 * @var $current_type Wpjb_ModelJobType Available if browsing jobs by type
 */

?>
<?php $query = new Daq_Db_Query(); $query->select("*")->from("Wpjb_Model_Category t1"); $query; $jcats = $query->execute(); ?>
<?php $query = new Daq_Db_Query(); $query->select("*")->from("Wpjb_Model_FieldOption t1")->where("field_id=2"); $query; $jcounties = $query->execute(); ?>

<div id="wpjb-main" class="wpjb-page-index">

    <?php the_field('jl_intro_paragraph'); ?>
    <?php if(get_field('jl_fun_photos')): ?>
        <?php while (has_sub_field('jl_fun_photos')): ?>
            <img src="<?php the_sub_field('jl_fun_photo_1') ?>" alt="Fun Photos">
        <?php endwhile; ?>
    <?php endif; ?>
    <div class="clearfix"></div>
    <form action="http://localhost/compasscares.com/jobs/find/" method="get" class="wpjb-form" id="searchjobs">
        <div class="wpjb-field">
            <input id="query" name="query" type="text" class="regular-text wpjb-auto-clear" value="" placeholder="Search (city, title, etc.)" />
            <input type="submit" name="wpjb_preview" id="wpjb_submit" value="" />
        </div>
    </form>
    <div class="clearfix"></div>
    
    <ul class="unstyled job-filters">
        <p>Filter by Postion:</p>
        <li><a href="<?php bloginfo('url'); ?>/jobs/">All Positions</a></li>
        <?php foreach($jcats as $jcat): ?>
            <li><a href="<?php bloginfo('url'); ?>/jobs/category/<?php echo $jcat->slug; ?>"><?php echo $jcat->title; ?></a></li>
        <?php endforeach; ?>
    </ul>
     
    <ul class="unstyled job-filters">
        <p>Filter by County:</p>
        <?php foreach($jcounties as $jcounty): ?>
            <li><input type='checkbox' id='<?php echo $jcounty->value; ?>' value='<?php echo $jcounty->value; ?>' /> <?php echo $jcounty->value; ?></li>
        <?php endforeach; ?>
    </ul>

    <?php wpjb_flash(); ?>

    <?php if(wpjb_description()): ?>
  
    <div><?php echo wpjb_description() ?></div>
    <?php endif; ?>

    
    <table id="wpjb-job-list" class="wpjb-table">
        <thead>
            <tr>
                <th><?php _e("Position", WPJB_DOMAIN) ?> </th>
                <th><?php _e("PT/FT", WPJB_DOMAIN) ?></th>
                <th><?php _e("Location", WPJB_DOMAIN) ?></th>
                <th class=""><?php _e("Date Posted", WPJB_DOMAIN) ?></th>
            </tr>
        </thead>
        <tbody>

            <tr class="no-jobs-row" style="display: none;">
                <td colspan="3" class="wpjb-table-empty">
		    <?php _e("No job listings found.", WPJB_DOMAIN); ?>
                </td>
            </tr>
         
        <?php if (!empty($jobList)) : foreach($jobList as $job): ?>
        <?php /* @var $job Wpjb_Model_Job */ ?>
            <tr class="<?php wpjb_job_features($job); ?>" data-category="jcat-<?php echo $job->job_category; ?>" data-county="<?php echo $job->getFieldValue('2')?>">
                <td class="wpjb-column-title" colspan="1">
                    <a class="jd"><?php esc_html_e($job->job_title) ?></a>
                </td>
                <td class="wpjb-column-ft_pt" colspan="1">
                   <?php esc_html_e($job->getType()->title) ?>
                </td>
                <td class="wpjb-column-location" colspan="1">
                    <?php esc_html_e($job->locationToString()) ?>
                </td>
                <td class="wpjb-column-date" colspan="1">
                    <?php echo wpjb_job_created_at("M, d", $job); ?>
                </td>
                
             </tr>
            
             <thead class="job-description">
                <tr>
                    <th colspan="4">
                        <div>
                            <p><?php echo $job->getFieldValue('3')?></p>
                            <a href="<?php echo wpjb_link_to("job", $job) ?>">More Info</a>
                        </div>
                    </th>
                </tr>
            </thead>
          </div>
                  
            <?php endforeach; else :?>
            
            <tr>
                <td colspan="3" class="wpjb-table-empty">
                    <?php _e("No job listings found.", WPJB_DOMAIN); ?>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="wpjb-paginate-links">
    
        <?php wpjb_paginate_links() ?>
        
    </div>
    
    <p>Don’t see your desired position available?  <a href="<?php bloginfo('url'); ?>/jobs/apply/any-position/">Fill out an application anyway!</a></p>

    
</div>
