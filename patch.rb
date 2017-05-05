# Step 1: make sure your working copy is at the new_version
# Step 2: configure the settings

require 'json'

# Settings
output = ARGV[0]
gitdir = ARGV[1]
new_version = 'v3.0.0-p3'
old_versions = ['v3.0.0-p1', 'v3.0.0-p2']
last_supported = old_versions[0]

# Get deleted files
deleted = []
for version in old_versions
  deleted += `git --git-dir #{File.join(gitdir, '.git')} diff --diff-filter=D --name-only #{version} #{new_version}`.split("\n")
  deleted.uniq!
end

# Get updated flies
updated = `git --git-dir #{File.join(gitdir, '.git')} diff --diff-filter=DM --name-only #{last_supported} #{new_version}`.split("\n")
updated << 'VERSION'

# Resolve the updated/deleted stuffs
intersected = deleted & updated
for e in intersected
  if File.exists?(e)
    deleted.delete_if { |i| i == e }
  else
    updated.delete_if { |i| i == e }
  end
end

# build the meta json
meta = { version: new_version, release_date: 'April, 30th 2017', last_supported:  last_supported, updated: updated, deleted: deleted }

# write to the output folder

`rm -fr #{output}; mkdir #{output}`
subdir = "patch-#{new_version}"
`mkdir #{File.join(output, subdir)}`
## write the meta information
File.open(File.join(output, subdir, 'meta.json'), 'w') do |f|
  f.write(meta.to_json)
end

## copy files
for file in meta[:updated]
  `cp --parent #{file} #{File.join(output, subdir)}`
end

## zip file
`cd #{output}; zip -r #{subdir}.zip #{subdir}; rm -fr #{subdir}`
