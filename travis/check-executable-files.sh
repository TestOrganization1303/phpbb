#!/bin/bash
#
# This file is part of the phpBB Forum Software package.
#
# @copyright (c) phpBB Limited <https://www.phpbb.com>
# @license GNU General Public License, version 2 (GPL-2.0)
#
# For full copyright and license information, please see
# the docs/CREDITS.txt file.
#
set -e

DB=$1
TRAVIS_PHP_VERSION=$2
path="$3"

if [ "$TRAVIS_PHP_VERSION" == "5.5" -a "$DB" == "mysqli" ]
then
	# Check the permissions of the files

	# Directories to skip
	directories_skipped="-path ${path}develop -o -path ${path}vendor"

	# Files to skip
	files_skipped="-name composer.phar"

	# Files which have to be executable
	executable_files="-path ${path}bin/\*"

	incorect_files=$( 								\
		find ${path}								\
			'('										\
				'('									\
					${directories_skipped}			\
				')'									\
				-a -type d -prune -a -type f		\
			')' -o 									\
			'('										\
				-type f -a							\
				-not '('							\
					${files_skipped}				\
				')' -a								\
				'('									\
					'('								\
						'('							\
							${executable_files}		\
						')' -a						\
						-not -perm +100				\
					')' -o							\
					'('								\
						-not '('					\
							${executable_files}		\
						')' -a						\
						-perm +111					\
					')'								\
				')'									\
			')'										\
		)

	if [ "${incorect_files}" != '' ]
	then
		ls -la ${incorect_files}
		echo "does not have the proper permissions.";
		exit 1;
	fi
fi
