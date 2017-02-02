#! /usr/bin/env python
# -*- coding: ISO-8859-15 -*-
#
# pkpgcounter : a generic Page Description Language parser
#
# (c) 2003, 2004, 2005, 2006 Jerome Alet <alet@librelogiciel.com>
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
#
# $Id: escp2.py 234 2006-09-14 22:47:46Z jerome $
#

"""This modules implements a page counter for ESC/P2 documents."""

import sys

import pdlparser

class Parser(pdlparser.PDLParser) :
    """A parser for ESC/P2 documents."""
    def isValid(self) :        
        """Returns True if data is ESC/P2, else False."""
        if self.firstblock.startswith("\033@") or \
           self.firstblock.startswith("\033*") or \
           self.firstblock.startswith("\n\033@") or \
           self.firstblock.startswith("\0\0\0\033\1@EJL") : # ESC/P Raster ??? Seen on Stylus Photo 1284
            self.logdebug("DEBUG: Input file is in the ESC/P2 format.")
            return True
        else :    
            return False
            
    def getJobSize(self) :    
        """Counts pages in an ESC/P2 document."""
        # with Gimpprint, at least, for each page there
        # are two Reset Printer sequences (ESC + @)
        marker1 = "\033@"
        
        # with other software or printer driver, we
        # may prefer to search for "\r\n\fESCAPE"
        # or "\r\fESCAPE"
        marker2r = "\r\f\033"
        marker2rn = "\r\n\f\033"
        
        # and ghostscript's stcolor for example seems to
        # output ESC + @ + \f for each page plus one
        marker3 = "\033@\f"
        
        # while ghostscript's escp driver outputs instead
        # \f + ESC + @
        marker4 = "\f\033@"
        
        data = self.infile.read()
        pagecount1 = data.count(marker1)
        pagecount2 = max(data.count(marker2r), data.count(marker2rn))
        pagecount3 = data.count(marker3)
        pagecount4 = data.count(marker4)
            
        if pagecount2 :    
            return pagecount2
        elif pagecount3 > 1 :     
            return pagecount3 - 1
        elif pagecount4 :    
            return pagecount4
        else :    
            return int(pagecount1 / 2)       
        
if __name__ == "__main__" :    
    pdlparser.test(Parser)
