/* jshint node:true */
//https://github.com/kswedberg/grunt-version
module.exports = {
	options: {
		pkg: {
			version: '<%= package.version %>'
		}
	},
	project: {
		src: [
			'package.json'
		]
	},
	style: {
		options: {
			prefix: 'Version\\:\\s'
		},
		src: [
			'elementemplator.php'

		]
	},
	constants: {
		options: {
			prefix: 'ET_VERSION\'\,\\s+\''
		},
		src: [
			'elementemplator.php',
		]
	}
};
