# Quickly make a patch
# Example: ruby genpatch.rb 4.0.25-p500 4.0.25-p507 /tmp/out/
# ==> produce a patch file /tmp/out/patch-4.0.25-p507.bin

require 'json'

# Settings
last_supported = ARGV[0]
new_version = ARGV[1]
output = ARGV[2]
gitdir = './'

packages = ['acelle']       # vendor packages to be replaced
dirs = ['public/builder', 'database', 'resources/lang/default']                   # 'resources/lang/default' for example

# Auto setting
release_date = Time.now.to_s
packages += ['autoload.php', 'composer'] unless (packages.empty? && dirs.empty?)

# Retrieve input
if last_supported == new_version
  puts "No upgrade package needed"
  exit(0)
end

history = `git --git-dir #{File.join(gitdir, '.git')}  --work-tree #{gitdir} log #{last_supported}..#{new_version} --pretty=format:"%h"`.split("\n")

# Get deleted files
deleted = []
updated = []
for version in history
  deleted += `git --git-dir #{File.join(gitdir, '.git')}  --work-tree #{gitdir} show --diff-filter=D --pretty='format:' --name-only #{version}`.split("\n")
  deleted += `git --git-dir #{File.join(gitdir, '.git')}  --work-tree #{gitdir} show --diff-filter=R --pretty='format:' --name-only #{version} | awk '{print $1}'`.split("\n")
  updated += `git --git-dir #{File.join(gitdir, '.git')}  --work-tree #{gitdir} show --diff-filter=ACMRTUXB --pretty='format:' --name-only #{version}`.split("\n")
end

updated << 'VERSION'

deleted.uniq!
updated.uniq!

skipped = [
  'patch.rb'
]

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

for f in updated
  unless File.exists?(f)
    skipped << f
  end
end

puts skipped.uniq.map{|f| "* Skipped: #{f}"}.join("\n")

updated.delete_if{ |f| skipped.include?(f) }

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
subdir = "patch-from-#{last_supported}-to-#{new_version}"
`mkdir #{File.join(output, subdir)}`

## write meta information
File.open(File.join(output, subdir, 'meta.json'), 'w') do |f|
  f.write(meta.to_json)
end

## copy files
for file in meta[:updated]
  if File.exists?(file)
    `cp -r --parent "#{file}" "#{File.join(output, subdir)}"`
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
  `mkdir -p #{File.join(output, subdir, File.dirname(dir))}`
  `cp -r #{dir} #{File.join(output, subdir, File.dirname(dir))}`
end

## zip file
`cd #{File.join(output, subdir)}; chmod 755 -R . ; zip -r ../#{subdir}.bin .; cd ..; rm -fr #{subdir}`

puts `readlink -f #{subdir}.bin`
