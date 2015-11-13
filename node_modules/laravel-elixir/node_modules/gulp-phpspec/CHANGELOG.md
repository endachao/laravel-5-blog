## gulp-phpspec Changelog

- 0.5.2: Bug Fixes
    - Fixed regression error in 0.5.0 release
    - Fixed test spec file which had partial bad spec construct
    
- 0.5.0: Complete refactoring 
    - Refacorted `index.js` and added library files (currently only phpspec)
    - This index.js refactor will be ported acosss all gulp-xxx plugins in future releases
    
- 0.4.2: Bug Fixes and Refactored Testing
    - Refactorting test cover to test beyond binary testing
    - Added gulp lint task
    
- 0.3.2: Bug Fixes
    - Fixed issue related to spec files not being completed due to missing callback

- 0.3.1: Asset modifications
    - Added new icons for pass and fail which can be used by notify plugin (see example below for usage)
      /assets/test-pass.png
      /assets/test-fail.png
    
    
- 0.3.0: Bug Fixes
  - refactored noInteraction option to match PHPSpec option (was called noInteract)

- 0.2.6: Added formatter option
  - added support for -f formatter options

- 0.2.5: Bug fix introduced in 0.2.4

- 0.2.4: Added options
    - added support for quiet option

- 0.2.3: Added options
    - added support for verbose flags
    - added support for no-interaction flag (on by default)
    - added flag for 'noAnsi' disabling ansi (false by default)

- 0.2.2: UI/UX Changes
    - added color output support using --ansi switch
    - removed Node 0.9 support from Travis integration

- 0.2.1: Code Refactor and Travis Integration
    - added travis configuration

- 0.2.0: Code Cleanup
    - Removed calls to console.log -> gutil.log (playing nice in the playground)
    - Fixed issue with calling as dependency task (thanks @taai)

- 0.1.1: Code Cleanup
    - Removed calls to console.log -> gutil.log (playing nice in the playground)

- 0.1.0: Initial Release
