#!/usr/bin/env python

import os
import sys
import sys, os.path
if os.path.exists(os.path.join(sys.path[0], 'hatch.py')):
    del sys.path[0]

import hatch.cli

if __name__ == "__main__":
    sys.exit(hatch.cli.main())
