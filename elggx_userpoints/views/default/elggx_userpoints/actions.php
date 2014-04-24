<?php

$plugin = elgg_get_plugin_from_id('elggx_userpoints');

$ts = time();
$token = generate_action_token($ts);

?>


<div class="elggx_userpoints_actions">
  <form method="POST" action="<?php echo elgg_get_site_url(); ?>action/elggx_userpoints/settings">
      <?php echo elgg_view('input/hidden', array('name' => '__elgg_token', 'value' => $token)); ?>
      <?php echo elgg_view('input/hidden', array('name' => '__elgg_ts', 'value' => $ts)); ?>

  <table>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr><td><h3><?php echo elgg_echo('userpoints_standard:activities'); ?></h3></td><td>&nbsp;</td></tr>
    <tr><td colspan="2"><hr /><br /></td></tr>

    <tr>
        <td width="40%"><label><?php echo elgg_echo('userpoints_standard:friend'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[friend]", 'value' => $plugin->friend)); ?></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td width="40%"><label><?php echo elgg_echo('userpoints_standard:blog'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[blog]", 'value' => $plugin->blog)); ?></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td width="40%"><label><?php echo elgg_echo('userpoints_standard:file'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[file]", 'value' => $plugin->file)); ?></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td width="40%"><label><?php echo elgg_echo('userpoints_standard:bookmarks'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[bookmarks]", 'value' => $plugin->bookmarks)); ?></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:comment'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[comment]", 'value' => $plugin->comment)); ?></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:riverpost'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[riverpost]", 'value' => $plugin->riverpost)); ?></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:thewire'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[thewire]", 'value' => $plugin->thewire)); ?></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:group'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[group]", 'value' => $plugin->group)); ?></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:groupforumtopic'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[groupforumtopic]", 'value' => $plugin->groupforumtopic)); ?></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:discussion_reply'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[discussion_reply]", 'value' => $plugin->discussion_reply)); ?></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:profile'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[profileupdate]", 'value' => $plugin->profileupdate)); ?></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:profileicon'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[profileicon]", 'value' => $plugin->profileicon)); ?></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:messageboard'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[messageboard]", 'value' => $plugin->messageboard)); ?></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:page_top'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[page_top]", 'value' => $plugin->page_top)); ?></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:likes'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[likes]", 'value' => $plugin->likes)); ?></td>
    </tr>

    <tr><td></td><td>&nbsp;</td></tr>
    <tr><td><h3><?php echo elgg_echo('userpoints_standard:polls'); ?></h3></td><td>&nbsp;</td></tr>
    <tr><td colspan="2"><hr /><br /></td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:poll'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[poll]", 'value' => $plugin->poll)); ?></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:pollvote'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[vote]", 'value' => $plugin->vote)); ?></td>
    </tr>

    <tr><td></td><td>&nbsp;</td></tr>
    <tr><td><h3><?php echo elgg_echo('userpoints_standard:classifieds'); ?></h3></td><td>&nbsp;</td></tr>
    <tr><td colspan="2"><hr /><br /></td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:add_classified'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[ad]", 'value' => $plugin->ad)); ?></td>
    </tr>

    <tr><td></td><td>&nbsp;</td></tr>
    <tr><td><h3><?php echo elgg_echo('userpoints_standard:izap_videos'); ?></h3></td><td>&nbsp;</td></tr>
    <tr><td colspan="2"><hr /><br /></td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:add_video'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[izap_videos]", 'value' => $plugin->izap_videos)); ?></td>
    </tr>

    <tr><td></td><td>&nbsp;</td></tr>
    <tr><td><h3><?php echo elgg_echo('userpoints_standard:tidypics'); ?></h3></td><td>&nbsp;</td></tr>
    <tr><td colspan="2"><hr /><br /></td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:create_album'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[album]", 'value' => $plugin->album)); ?></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:upload_photo'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[image]", 'value' => $plugin->image)); ?></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:phototag'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[phototag]", 'value' => $plugin->phototag)); ?></td>
    </tr>

    <tr><td></td><td>&nbsp;</td></tr>
    <tr><td><h3><?php echo elgg_echo('userpoints_standard:fivestar'); ?></h3></td><td>&nbsp;</td></tr>
    <tr><td colspan="2"><hr /><br /></td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:fivestar_vote'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[fivestar]", 'value' => $plugin->fivestar)); ?></td>
    </tr>

    <tr><td></td><td>&nbsp;</td></tr>
    <tr><td><h3><?php echo elgg_echo('userpoints_standard:recommendations'); ?></h3></td><td>&nbsp;</td></tr>
    <tr><td colspan="2"><hr /><br /></td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:recommendations:points'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[recommendation]", 'value' => $plugin->recommendation)); ?></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:recommendations:approve'); ?></label></td>
        <td><?php echo elgg_view('input/dropdown', array(
                         'name' => 'params[recommendations_approve]',
                         'options_values' => array('1' => elgg_echo('elggx_userpoints:settings:yes'), '0' => elgg_echo('elggx_userpoints:settings:no')),
                         'value' => $plugin->delete
                         )
                    );
             ?>
        </td>
    </tr>

    <tr><td></td><td>&nbsp;</td></tr>
    <tr><td><h3><?php echo elgg_echo('userpoints_standard:loginsettings'); ?></h3></td><td>&nbsp;</td></tr>
    <tr><td colspan="2"><hr /><br /></td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:login'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[login]", 'value' => $plugin->login)); ?></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:login_threshold'); ?></label></td>
        <td><?php $login_threshold = $plugin->login_threshold; ?>
          <select name="params[login_threshold]">
            <option value="3600" <?php if ($login_threshold == '3600') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('userpoints_standard:1hour'); ?></option>
            <option value="14400" <?php if ($login_threshold == '14400') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('userpoints_standard:4hours'); ?></option>
            <option value="28800" <?php if ($login_threshold == '28800') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('userpoints_standard:8hours'); ?></option>
            <option value="43200" <?php if ($login_threshold == '43200') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('userpoints_standard:12hours'); ?></option>
            <option value="86400" <?php if ($login_threshold == '86400') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('userpoints_standard:1day'); ?></option>
            <option value="604800" <?php if ($login_threshold == '604800') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('userpoints_standard:1week'); ?></option>
            <option value="2592000" <?php if ($login_threshold == '2592000') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('userpoints_standard:1month'); ?></option>
          </select>
        </td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:login_interval'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[login_interval]", 'value' => $plugin->login_interval)); ?></td>
    </tr>

    <tr><td></td><td>&nbsp;</td></tr>
    <tr><td><h3><?php echo elgg_echo('userpoints_standard:invitesettings'); ?></h3></td><td>&nbsp;</td></tr>
    <tr><td colspan="2"><hr /><br /></td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:invite'); ?></label></td>
        <td><?php echo elgg_view('input/text', array('name' => "params[invite]", 'value' => $plugin->invite)); ?></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:verify_email'); ?></label></td>
        <td><?php echo elgg_view('input/dropdown', array(
                             'name' => 'params[verify_email]',
                             'options_values' => array('1' => elgg_echo('elggx_userpoints:settings:yes'), '0' => elgg_echo('elggx_userpoints:settings:no')),
                             'value' => $plugin->verify_email
                             )
                        );
            ?>
        </td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:require_registration'); ?></label></td>
        <td><?php echo elgg_view('input/dropdown', array(
                             'name' => 'params[require_registration]',
                             'options_values' => array('1' => elgg_echo('elggx_userpoints:settings:yes'), '0' => elgg_echo('elggx_userpoints:settings:no')),
                             'value' => $plugin->require_registration
                             )
                        );
            ?>
        </td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:expire_invite'); ?></label></td>
        <td><?php echo elgg_view('input/dropdown', array(
                    'name' => 'params[expire_invite]',
                    'options_values' => array(
                        '0'        => elgg_echo('elggx_userpoints:settings:never'),
                        '3600'     => elgg_echo('elggx_userpoints:settings:1_hour'),
                        '86400'    => elgg_echo('elggx_userpoints:settings:1_day'),
                        '604800'   => elgg_echo('elggx_userpoints:settings:1_week'),
                        '1209600'  => elgg_echo('elggx_userpoints:settings:2_weeks'),
                        '2419200'  => elgg_echo('elggx_userpoints:settings:4_weeks'),
                        '31536000' => elgg_echo('elggx_userpoints:settings:365_days')
                    ),
                    'value' => $plugin->expire_invite
           )); ?>
        </td>
    </tr>

    <tr><td></td><td>&nbsp;</td></tr>
    <tr><td><h3><?php echo elgg_echo('userpoints_standard:misc'); ?></h3></td><td>&nbsp;</td></tr>
    <tr><td colspan="2"><hr /><br /></td></tr>

    <tr>
        <td><label><?php echo elgg_echo('userpoints_standard:delete'); ?></label></td>
        <td><?php echo elgg_view('input/dropdown', array(
                             'name' => 'params[delete]',
                             'options_values' => array('1' => elgg_echo('elggx_userpoints:settings:yes'), '0' => elgg_echo('elggx_userpoints:settings:no')),
                             'value' => $plugin->delete
                             )
                        );
            ?>
        </td>
    </tr>

  </table>

    <br>

    <?php echo elgg_view('input/submit', array('value' => elgg_echo("save"))); ?>

  </form>
</div>
