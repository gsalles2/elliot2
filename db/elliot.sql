-- JASmine, print accounting system for Cups.
-- Copyright (C) Nayco.
--
-- (Please read the COPYING file)
--
-- This program is free software; you can redistribute it and/or
-- modify it under the terms of the GNU General Public License
-- as published by the Free Software Foundation; either version 2
-- of the License, or (at your option) any later version.
--
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with this program; if not, write to the Free Software
-- Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

-- Database: print
-- Table: 'jobs_log'
--
CREATE TABLE jobs_log (
  id mediumint(9) NOT NULL auto_increment,
  date timestamp NOT NULL,
  job_id tinytext NOT NULL,
  printer tinytext NOT NULL,
  user tinytext NOT NULL,
  server tinytext NOT NULL,
  title tinytext NOT NULL,
  copies smallint(6) NOT NULL default '0',
  pages smallint(6) NOT NULL default '0',
  options tinytext NOT NULL,
  doc tinytext NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM
COMMENT='Lists all the jobs successfully sent for printing';
