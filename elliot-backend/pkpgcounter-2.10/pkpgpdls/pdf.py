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
# $Id: pdf.py 234 2006-09-14 22:47:46Z jerome $
#

"""This modules implements a page counter for PDF documents."""

import sys
import re

import pdlparser

class PDFObject :
    """A class for PDF objects."""
    def __init__(self, major, minor, description) :
        """Initialize the PDF object."""
        self.major = major
        self.minor = minor
        self.description = description
        self.comments = []
        self.content = []
        self.parent = None
        self.kids = []
        
class Parser(pdlparser.PDLParser) :
    """A parser for PDF documents."""
    totiffcommand = 'gs -sDEVICE=tiff24nc -dPARANOIDSAFER -dNOPAUSE -dBATCH -dQUIET -r%(dpi)i -sOutputFile="%(fname)s" -'
    def isValid(self) :    
        """Returns True if data is PDF, else False."""
        if self.firstblock.startswith("%PDF-") or \
           self.firstblock.startswith("\033%-12345X%PDF-") or \
           ((self.firstblock[:128].find("\033%-12345X") != -1) and (self.firstblock.upper().find("LANGUAGE=PDF") != -1)) or \
           (self.firstblock.find("%PDF-") != -1) :
            self.logdebug("DEBUG: Input file is in the PDF format.")
            return True
        else :    
            return False
        
    def getJobSize(self) :    
        """Counts pages in a PDF document."""
        # First we start with a generic PDF parser.
        lastcomment = None
        objects = {}
        inobject = 0
        objre = re.compile(r"\s?(\d+)\s+(\d+)\s+obj[<\s/]?")
        for fullline in self.infile.xreadlines() :
            parts = [ l.strip() for l in fullline.splitlines() ]
            for line in parts :
                if line.startswith("% ") :    
                    if inobject :
                        obj.comments.append(line)
                    else :
                        lastcomment = line[2:]
                else :
                    # New object begins here
                    result = objre.search(line)
                    if result is not None :
                        (major, minor) = [int(num) for num in line[result.start():result.end()].split()[:2]]
                        obj = PDFObject(major, minor, lastcomment)
                        obj.content.append(line[result.end():])
                        inobject = 1
                    elif line.startswith("endobj") \
                      or line.startswith(">> endobj") \
                      or line.startswith(">>endobj") :
                        # Handle previous object, if any
                        if inobject :
                            # only overwrite older versions of this object
                            # same minor seems to be possible, so the latest one
                            # found in the file will be the one we keep.
                            # if we want the first one, just use > instead of >=
                            oldobject = objects.setdefault(major, obj)
                            if minor >= oldobject.minor :
                                objects[major] = obj
                            inobject = 0        
                    else :    
                        if inobject :
                            obj.content.append(line)
                        
        # Now we check each PDF object we've just created.
        # colorregexp = re.compile(r"(/ColorSpace) ?(/DeviceRGB|/DeviceCMYK)[/ \t\r\n]", re.I)
        newpageregexp = re.compile(r"(/Type)\s?(/Page)[/\s]", re.I)
        pagecount = 0
        for obj in objects.values() :
            content = "".join(obj.content)
            count = len(newpageregexp.findall(content))
            pagecount += count
        return pagecount    
        
if __name__ == "__main__" :    
    pdlparser.test(Parser)
