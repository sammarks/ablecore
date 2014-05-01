core = 7
api = 2

; Modules
; -------

projects[drupal][version] = 7.26
projects[drupal][patch][] = "https://drupal.org/files/issues/drupal7_1978176_32_menu_load_objects.patch"
projects[drupal][patch][] = "https://drupal.org/files/issues/drupal7.entity-system.1525176-143.patch"

projects[admin_menu][subdir] = "contrib"
projects[admin_menu][version] = "3.0-rc4"

projects[adminimal_admin_menu][subdir] = "contrib"
projects[adminimal_admin_menu][version] = "1.5"
projects[adminimal_theme][subdir] = "contrib"
projects[adminimal_theme][version] = "1.2"

projects[ctools][subdir] = "contrib"
projects[ctools][version] = "1.4"

projects[nodequeue][subdir] = "contrib"
projects[nodequeue][version] = "2.0-beta1"

projects[pathauto][subdir] = "contrib"
projects[pathauto][version] = "1.2"

projects[module_filter][subdir] = "contrib"
projects[module_filter][version] = "2.0-alpha2"

projects[token][subdir] = "contrib"
projects[token][version] = "1.5"

projects[entity][subdir] = "contrib"
projects[entity][version] = "1.4"

projects[xautoload][subdir] = "contrib"
projects[xautoload][version] = "4.5"

projects[less][subdir] = "contrib"
projects[less][version] = "3.0"

projects[libraries][subdir] = "contrib"
projects[libraries][version] = "2.2"

projects[devel][subdir] = "contrib"
projects[devel][version] = "1.4"

projects[date][subdir] = "contrib"
projects[date][version] = "2.7"

projects[uuid][subdir] = "contrib"
projects[uuid][version] = "1.0-alpha5"

projects[menu_entity][type] = "module"
projects[menu_entity][subdir] = "contrib"
projects[menu_entity][download][type] = "git"
projects[menu_entity][download][url] = "sammarks15@git.drupal.org:sandbox/sammarks15/2231077.git"
projects[menu_entity][download][branch] = "7.x-1.x"

; Libraries
; ---------

libraries[lessphp][type] = "libraries"
libraries[lessphp][download][type] = "git"
libraries[lessphp][download][url] = "https://github.com/leafo/lessphp.git"

; AE Libraries
; ------------

projects[ablecore][type] = "module"
projects[ablecore][subdir] = "contrib"
projects[ablecore][download][type] = "git"
projects[ablecore][download][url] = "https://github.com/sammarks/ablecore.git"
projects[ablecore][download][branch] = "7.x-1.x"
