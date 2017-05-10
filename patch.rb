# Step 1: make sure your working copy is at the new_version
# Step 2: configure the settings
# Step 3: double check the composer.json

require 'json'

# Settings
new_version = 'v3.0.0-p3'                 # the version of this build
release_date = 'April, 30th 2017'         # format?
old_versions = ['v3.0.0-p1', 'v3.0.0-p2'] # sort by chronological order
packages = ['guzzlehttp']                             # vendor packages to be replaced

packages += ['autoload.php', 'composer'] unless packages.empty?
last_supported = old_versions[0]

# Retrieve input
output = ARGV[0]
gitdir = ARGV[1]

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
# Make sure your working copy is of the correct version: "git checkout v3.0.0-p3" for example
intersected = deleted & updated
for e in intersected
  if File.exists?(e)
    deleted.delete_if { |i| i == e }
  else
    updated.delete_if { |i| i == e }
  end
end

# build the meta json
meta = {
  version: new_version,
  release_date: release_date,
  last_supported: last_supported,
  updated: updated,
  deleted: deleted,
  packages: packages
}

# prepare output folder
`rm -fr #{output}; mkdir #{output}`
subdir = "patch-#{new_version}"
`mkdir #{File.join(output, subdir)}`

## write meta information
File.open(File.join(output, subdir, 'meta.json'), 'w') do |f|
  f.write(meta.to_json)
end

## copy files
for file in meta[:updated]
  `cp --parent #{file} #{File.join(output, subdir)}`
end

## copy packages and loaders
`mkdir #{File.join(output, subdir, 'vendor/')}` unless meta[:packages].empty?
for file in meta[:packages]
  path = "vendor/#{file}"
  `rm -fr #{File.join(output, subdir, path)}`
  `cp -r #{path} #{File.join(output, subdir, 'vendor/')}`
end

## zip file
`cd #{File.join(output, subdir)}; zip -r ../#{subdir}.acm .; cd ..; rm -fr #{subdir}`
