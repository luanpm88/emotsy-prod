# Step 1: make sure your working copy is at the new_version
# Step 2: configure the settings
# Step 3: double check the composer.json

require 'json'

# Settings
old_versions = ['3.0.15']         # sort by chronological order
packages = []                     # vendor packages to be replaced
dirs = ['resource/lang/default']  # 'resources/lang/default' for example

# Auto setting
release_date = Time.now.to_s
packages += ['autoload.php', 'composer'] unless packages.empty?
last_supported = old_versions[0]

# Retrieve input
new_version = ARGV[2]
output = ARGV[0]
gitdir = ARGV[1]

# Get deleted files
deleted = []
for version in old_versions
  deleted += `git --git-dir #{File.join(gitdir, '.git')}  --work-tree #{gitdir} diff --diff-filter=D --name-only #{version} #{new_version}`.split("\n")
  deleted.uniq!
end

# Get updated flies
updated = `git --git-dir #{File.join(gitdir, '.git')}  --work-tree #{gitdir} diff --diff-filter=AM --name-only #{last_supported} #{new_version}`.split("\n")

updated << 'VERSION'
updated.uniq!

skipped = [
  'public/images/default_site_logo_small_dark.png',
  'public/images/default_site_logo_small_light.png',
  'patch.rb'
]

for f in updated
  unless File.exists?(f)
    skipped << f
  end
end

puts skipped.uniq.map{|f| "* Skipped: #{f}"}.join("\n")

updated.delete_if{ |f| skipped.include?(f) }

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

if !updated.select{|f| f[/resources.lang.en.[a-z0-9]+\.php$/] }.empty?
  dirs << 'resources/lang/default'
end

# build the meta json
meta = {
  version: new_version,
  release_date: release_date,
  last_supported: last_supported,
  updated: updated,
  deleted: deleted,
  packages: packages,
  dirs: dirs
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
  if File.exists?(file)
    `cp --parent #{file} #{File.join(output, subdir)}`
  else
    puts "File #{file} skipped!"
  end
end

## copy packages and loaders
`mkdir #{File.join(output, subdir, 'vendor/')}` unless meta[:packages].empty?
for file in meta[:packages]
  path = "vendor/#{file}"
  `rm -fr #{File.join(output, subdir, path)}`
  `cp -r #{path} #{File.join(output, subdir, 'vendor/')}`
end

for dir in meta[:dirs]
  `rm -fr  #{File.join(output, subdir, dir)}`
  `cp -r #{dir} #{File.join(output, subdir, dir)}`
end

## zip file
`cd #{File.join(output, subdir)}; zip -r ../#{subdir}.bin .; cd ..; rm -fr #{subdir}`
