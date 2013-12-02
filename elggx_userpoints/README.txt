Elggx Userpoints plugin for Elgg 1.9
Latest Version: 1.9.7
Released: 2013-11-12
Contact: iionly@gmx.de
License: GNU General Public License version 2
Copyright: (c) iionly (for Elgg 1.8 version), Billy Gunn


With the userpoints plugin users can gain or lose points for performing certain actions on your site like posting blogs, comments, editing their profile, adding pictures, videos etc. The plugins also provides an API to be useable by other plugins to make use of the userpoints budget of users (for example used by the Gifts plugin).



Installation:

1. Copy the elggx_userpoints folder into the mod directory of your Elgg installation,
2. Enable the plugin in the admin section of your site,
3. Configure then the plugin settings (section "Administer" - "Utilities" - "Elggx Userpoints"). Especially, you need to configure the number of points to be awared for specific user actions on the Point Settings tab.



Changelog:

1.9.7 (iionly):

- Updated version 1.8.7 for Elgg 1.9.


1.8.7 (iionly):

- Fixed awarding of points on starting a new group discussion topic and replying in a group discussion.


1.9.6 (iionly):

- Updated for Elgg 1.9,
- Supports addition and (new) substraction of userpoints for comments (possible as comments are now saved as ElggComment objects and no longer as annotations on Elgg 1.9).


1.8.6 (iionly):

- Spanish language file added (thanks to Gonzalo / GEARinvent).


1.8.5 (iionly):

- Fixed pagination on list, detail and moderate tabs on Elggx Userpoints plugin settings page.


1.8.4 (iionly):

- New admin options to restore (re-calculate) a user's userpoints or all users' userpoints. This option might be useful in case the metadata entry containing a user's current number of userpoints gets corrupted due to server glitches.


1.8.3 (iionly):

- Added a German language file,
- Index page widget added (useable with Widget Manager plugin),
- Branding (e.g. configuring the words to be used for the "Points" terms) moved from plugin settings to language files (to allow for branding to be configured in more than one language),
- Better visibility of "Reset All Points" link by making it an Elgg Action button,
- Some general code cleanup,
- Fixed userpoints handling of Tidypics upload images and create albums actions,
- Fixed userpoints handling of invitations (among getting it to work at all Siteaccess plugin and Contact_importer plugin are now no longer support in invitation userpoint handling).

Remarks about userpoints for invitation of friends:

- The same person (related to the email address used for the invitation) can be invited by different users and each of these users will be awarded points for inviting this person.
- You can opt for userpoints only to be awarded after the invited person joined the site or to be awarded already when the invitation is sent. In both cases a pending userpoint entry will be generated (appearing on the "Moderate" tab). In the first case the pending userpoints entry will contain the userpoints to be awarded to the user when the invited person joins the site. In the latter case the userpoints will be awarded immediately and the pending userpoints entry (0 points pending) will serve as blocking mechanism preventing the same user from inviting the same person more than once.
- If you use the Uservalidationbyemail plugin the pending userpoints will only be awarded after the account of the invited user is validated (or an admin validates the account). If you don't use this plugin the points are awarded when the user registers an account already.
- If you use the expiration date plugin you can define an interval for pending userpoints for outstanding invitations to expire. Or in case you opt for userpoint for invitations to be awarded immediately the pending userpoints entry blocking the re-invitations of the same person will expire.


1.8.2 (iionly):

- Fixed a deprecated issue introduced with Elgg 1.8.4.


1.8.1beta4 (iionly):

- A fix needed for the Gifts plugin to work (or all other plugins that want to use the functions userpoints_exists() and userpoints_get()).


1.8.1beta3 (iionly):

- Points can now be awarded additionally for: uploading a file, posting on a message board, adding a bookmark, liking an item and making another member a friend.


1.8.1beta2 (iionly):

- Fully working plugin settings and userpoints administration menu,
- All deprecated notices fixed,
- Fully multi-language support.


1.8.1beta (iionly):

- Deprecated notices for Profile-/Dashboard-Widget fixed,
- Settings menu in admin section.


r0 (iionly):

- Initial release for Elgg 1.8.


1.2.3 (latest version published by Billy Gunn):

- for Elgg 1.7 or earlier.
