#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

"$DIR"/incapsula site:setCacheRules "$1" -p "$2"
"$DIR"/incapsula site:setSecurity "$1" -p "$2"
"$DIR"/incapsula site:addHttpCacheRule "$1" -p "$2"
