#!/bin/sh

rm -rf src/Proto
mkdir src/Proto
protoc --php_out=src/Proto collector.proto

# fix namespaces
mv src/Proto/Hoten/OpenTracingBundle/Proto/* src/Proto
rm -rf src/Proto/Hoten

sed -i '' -e 's/GPBMetadata/Hoten\\OpenTracingBundle\\Proto\\GPBMetadata/g' src/Proto/GPBMetadata/Collector.php
sed -i '' -e 's/GPBMetadata/Hoten\\OpenTracingBundle\\Proto\\GPBMetadata/g' src/Proto/CollectPacket.php
