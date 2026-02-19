/**
* Get team logo URL (handles custom uploads vs sport icons)
* @param array $team - Team data including logo and sport info
* @return string - Full URL to logo/icon
*/
function getTeamLogo($team)
{
// If team has custom logo uploaded
if (!empty($team['logo'])) {
$logo_path = UPLOAD_PATH . '/teams/' . $team['logo'];
if (file_exists($logo_path)) {
return getBaseUrl() . 'assets/uploads/teams/' . $team['logo'];
}
}

// Fallback to sport icon
if (!empty($team['sport_icon'])) {
return $team['sport_icon']; // This is emoji, will be displayed as text
}

return '🏆'; // Default trophy icon
}